import { ref, computed, watch, toRaw, watchEffect } from "vue";
import { useForm } from "@inertiajs/vue3";
import usePetAI from "@/Composables/usePetAI.js";
import usePetSchedule from "@/Composables/usePetAI.js";
import useRoute from "@/Composables/useRoute.js";
import dt from "@/Lib/datetime.js";

function fillFields(fieldList, values) {
    const filled = {};
    for (const field of fieldList) {
        // convert UTC dates to local
        if (/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}/.test(values[field])) {
            filled[field] = values[field] ? dt.utcToLocal(values[field]) : null;
        } else {
            filled[field] = values[field] || null;
        }
    }
    return filled;
}

function fillArrayFields(fieldList, valueArray) {
    const filled = [];
    for (item of valueArray) {
        filled.push(fillFields(fieldList, item));
    }
    return filled;
}

export default function useSettingsForm(pet, settingGroup, options = {}) {

    const { fields = [], useSchedule = false } = options;

    const settings = pet.settings?.[settingGroup] || {};
    const formSettings = fillFields(fields.length ? fields : settings.keys(), settings);

    const { schedule, eventFields, blankEvent } = useSchedule ? usePetSchedule(pet, settingGroup) : {
        schedule: null,
        eventFields: []
    };
    const formSchedule = schedule ? (fillArrayFields(eventFields, schedule) || []) : [];

    const form = useForm({
        ...formSettings,
        schedule: formSchedule
    });
    console.log("init form", toRaw(form.data()));

    function addEvent(event) {
        form.schedule.push(event || blankEvent);
    }

    // watch(() => form.schedule, (newSchedule)=>{
    //     if (newSchedule.length) {
    //         const filteredSchedule = toRaw(newSchedule)?.filter((filterEvent) => {
    //             if (!filterEvent['preset']) { console.log('removing blank', filterEvent) }
    //             return filterEvent['preset']
    //         })
    //         if (filteredSchedule.length !== newSchedule.length) form.schedule = newSchedule
    //         console.log('after removal',toRaw(form.schedule))
    //     }
    // }, {deep: true})

    const isSuggestion = ref(false);

    const suggestMessage = computed(() => {
        return isSuggestion.value ? "Suggested Values - Edit or" : null;
    });

    const suggestSettings = (suggestions) => {
        for (const field in suggestions) {
            if (suggestions[field]) {
                isSuggestion.value = true;
            }
            if (field === "event") {
                addEvent(suggestions[field]);
            } else {
                form[field] = field === "last" ? dt.lastTimeElapsed([form.time_1, form.time_2], form.interval, form.unit) : suggestions[field];
            }
        }
    };

    const saveSettings = () => {
        isSuggestion.value = false;
        form.transform((data) => {
            const saveData = {};
            if (data.schedule) {
                saveData["schedule"] = {
                    category: settingGroup,
                    ...data.schedule
                };
                saveData.schedule.last = saveData.schedule.last ? dt.localToUtc(saveData.schedule.last) : null;
                delete (data.schedule);
            }
            saveData[settingGroup] = {
                ...data,
            };
            return saveData;
        }).patch(route("pets.saveSettings", pet._id), {
            preserveScroll: true,
            onSuccess: () => {
                usePetAI(pet, { name: settingGroup });
                useRoute({ name: "pets.settings", params: pet._id });
            },
            onError: () => {
                // clear errors when the form changes
                let unwatch = watch(form, () => {
                    form.clearErrors();
                    unwatch();
                });
                useRoute({ name: "pets.settings", params: pet._id });
            },
        });
    };

    return { form, addEvent, isSuggestion, suggestMessage, saveSettings, suggestSettings };
}