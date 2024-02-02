<script setup>
import { computed, ref, toRaw, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import ButtonPrimary from "@/Components/ButtonPrimary.vue";
import InputText from "@/Components/InputText.vue";
import InputCheckbox from "@/Components/InputCheckbox.vue";
import InputButtons from "@/Components/InputButtons.vue";
import FAIcon from "@/Components/FAIcon.vue";
import useRoute from "@/Composables/useRoute.js";
import usePetAI from "@/Composables/usePetAI.js";

const props = defineProps({
    pet: {
        type: Object,
        required: true
    },
    meta: {
        type: Object,
        default: {}
    },
});

const settingGroup = "poop";

const speciesOptions = computed(() => {
    return toRaw(props.meta.species).filter(function(specie) {
        return specie.value === props.pet.species;
    })[0][settingGroup];
});

const suggestValues = ref(speciesOptions?.value?.length === 1);

const autofillMessage = computed(() => {
    return suggestValues.value ? "Suggested Values - Edit or" : null;
});

const settings = ref(props.pet?.settings?.[settingGroup] || {});

const form = useForm({
    [settingGroup]: {
        location: settings.value.location,
        time_1: settings.value.time_1,
        time_2: settings.value.time_2,
        interval: settings.value.interval,
        door: settings.value.door
    }
});

// suggest based on location
watch(() => form[settingGroup].location, () => {

    switch (form[settingGroup].location) {
        case "walks":
            if (!form[settingGroup].time_1 || form[settingGroup].time_1 === "00:00") {
                form[settingGroup].time_1 = "07:00";
                suggestValues.value = true;
            }
            if (!form[settingGroup].time_2 || form[settingGroup].time_2 === "00:00") {
                form[settingGroup].time_2 = "19:00";
                suggestValues.value = true;
            }
            delete form[settingGroup].interval;
            form[settingGroup].door = null;
            break;
        case "yard":
            if (form[settingGroup].door) {
                delete form[settingGroup].time_1;
                delete form[settingGroup].time_2;
            } else {
                if (!form[settingGroup].time_1 || form[settingGroup].time_1 === "00:00") {
                    form[settingGroup].time_1 = "07:00";
                    suggestValues.value = true;
                }
                if (!form[settingGroup].time_2 || form[settingGroup].time_2 === "00:00") {
                    form[settingGroup].time_2 = "19:00";
                    suggestValues.value = true;
                }
            }
            delete form[settingGroup].interval;
            break;
        case "pasture":
            if (form[settingGroup].door) {
                if (!form[settingGroup].time_1 || form[settingGroup].time_1 === "00:00") {
                    form[settingGroup].time_1 = "07:00";
                    suggestValues.value = true;
                }
                if (!form[settingGroup].time_2 || form[settingGroup].time_2 === "00:00") {
                    form[settingGroup].time_2 = "19:00";
                    suggestValues.value = true;
                }
            } else {
                delete form[settingGroup].time_1;
                delete form[settingGroup].time_2;
            }
            delete form[settingGroup].interval;
            break;
        case "cage":
        case "litterbox":
        case "kennel":
            if (!form[settingGroup].interval) {
                form[settingGroup].interval = 2;
                suggestValues.value = true;
            }
            delete form[settingGroup].time_1;
            delete form[settingGroup].time_2;
            form[settingGroup].door = null;
            break;
        case "stable":
        case "tank":
            if (!form[settingGroup].interval) {
                form[settingGroup].interval = 7;
                suggestValues.value = true;
            }
            delete form[settingGroup].time_1;
            delete form[settingGroup].time_2;
            form[settingGroup].door = null;
            break;
        case "aviary":
        case "pond":
            delete form[settingGroup].time_1;
            delete form[settingGroup].time_2;
            delete form[settingGroup].interval;
            form[settingGroup].door = null;
            break;
        default:
    }
});

const showDoor = computed(() => {
    return ["yard", "pasture"].includes(form[settingGroup].location);
});

watch(() => form[settingGroup].door, () => {
    if (form[settingGroup].location === "pasture" ^ form[settingGroup].door) {
        delete form[settingGroup].time_1;
        delete form[settingGroup].time_2;
    } else if (form[settingGroup].location === "pasture" && form[settingGroup].door) {
        if (!form[settingGroup].time_1 || form[settingGroup].time_1 === "00:00") {
            form[settingGroup].time_1 = "07:00";
            suggestValues.value = true;
        }
        if (!form[settingGroup].time_2 || form[settingGroup].time_2 === "00:00") {
            form[settingGroup].time_2 = "19:00";
            suggestValues.value = true;
        }
    }
});

const doorTitle = computed(() => {
    switch (form[settingGroup].location) {
        case "yard":
            return "has Pet Door";
        case "pasture":
            return "Let Out";
        default:
            return null;
    }
});

const cleanSchedule = computed(() => {
    return ["kennel", "litterbox", "tank", "cage", "stable"].includes(form[settingGroup].location);
});

const showTimes = computed(() => {
    return ["yard", "walks"].includes(form[settingGroup].location) && !form[settingGroup].door ||
        ["pasture"].includes(form[settingGroup].location) && form[settingGroup].door;
});

const timeTitle1 = computed(() => {

    switch (form[settingGroup].location) {
        case "yard":
        case "pasture":
        case "kennel":
            return "Let Out Time";
        case "walks":
            return "Walk 1 Time";
        default:
            return null;
    }
});

const timeTitle2 = computed(() => {

    switch (form[settingGroup].location) {
        case "yard":
        case "pasture":
        case "kennel":
            return "Let In Time";
        case "walks":
            return "Walk 2 Time";
        default:
            return null;
    }
});

const isSavable = computed(() => {
    return form.isDirty && (form[settingGroup].location === "yard" ||
        form[settingGroup].location === "pond" ||
        form[settingGroup].location === "pasture" ||
        form[settingGroup].location === "aviary" ||
        ((form[settingGroup].location === "litterbox" || form[settingGroup].location === "kennel" || form[settingGroup].location === "cage" || form[settingGroup].location === "tank" || form[settingGroup].location === "stable") && form[settingGroup].interval) ||
        (form[settingGroup].location === "walks" && form[settingGroup].time_1)
    );
});

const saveSettings = () => {
    suggestValues.value = false;
    form.patch(route("pets.saveSettings", props.pet._id), {
        preserveScroll: true,
        onSuccess: () => {
            usePetAI(props.pet, { name: settingGroup });
            useRoute({ name: "pets.settings", params: props.pet._id });
        },
        onError: () => {
            // clear errors when the form changes
            let unwatch = watch(form, () => {
                form.clearErrors();
                unwatch();
            });
            useRoute({ name: "pets.settings", params: props.pet._id });
        },
    });
};
</script>

<template>
    <section>
        <h2 class="ml-2">
            <FAIcon class="text-2xl" name="poop"/>
            Poop
        </h2>

        <form class="border px-4 pb-1 pt-5 -mt-3 space-y-2 lg:p-6 lg:pb-2" @submit.prevent="saveSettings">

            <div class="flex flex-wrap items-center gap-2">

                <div class="grow min-w-1/2 mb-2">
                    <InputLabel for="location" value="Location"/>
                    <InputButtons id="location" v-model="form[settingGroup].location" :options="speciesOptions"
                                  class="gap-2"/>
                </div>

                <div v-if="cleanSchedule" class="min-w-1/2 mb-2">
                    <InputLabel
                            :value="'Clean ' + (form[settingGroup].location && form[settingGroup].location[0].toUpperCase() + form[settingGroup].location.slice(1))"
                            for="interval"/>
                    <div class="flex gap-2 items-center">
                        <div class="ml-2">every</div>
                        <div>
                            <InputText
                                    id="interval"
                                    v-model="form[settingGroup].interval"
                                    autocomplete="time-1"
                                    class="block w-[60px] mt-1"
                                    max="30"
                                    min="0"
                                    type="number"
                            />
                        </div>
                        <div>days</div>
                    </div>
                </div>

                <div v-if="showDoor" class="grow min-w-1/2 mb-2 mt-4 text-center">
                    <InputCheckbox id="petDoor" v-model="form[settingGroup].door"
                                   :checked="form[settingGroup].door"/>
                    <InputLabel :value="doorTitle" class="inline pl-2" for="petDoor"/>
                </div>

                <div v-if="showTimes"
                     class="grow min-w-1/2 mb-2 flex flex-wrap items-center gap-2">
                    <div class="grow min-w-1/2">
                        <InputLabel :value="timeTitle1" for="time_1"/>
                        <InputText
                                id="time_1"
                                v-model="form[settingGroup].time_1"
                                autocomplete="time-1"
                                class="block w-full mt-1"
                                type="time"
                        />
                    </div>
                    <div v-if="showTimes"
                         class="grow min-w-1/2">
                        <InputLabel :value="timeTitle2" for="time_2"/>
                        <InputText
                                id="time_2"
                                v-model="form[settingGroup].time_2"
                                autocomplete="time-2"
                                class="block w-full mt-1"
                                type="time"
                        />
                    </div>
                </div>
            </div>

            <div v-show="isSavable || form.recentlySuccessful || form.hasErrors"
                 class="text-center pb-2 flex justify-center items-center gap-3 font-medium text-slate-400">
                <div>
                    <InputError :message="form.errors[settingGroup + '.location']" class="mb-1"/>
                    <InputError :message="form.errors[settingGroup + '.time_1']" class="mb-1"/>
                    <InputError :message="form.errors[settingGroup + '.time_2']" class="mb-1"/>
                    <InputError :message="form.errors[settingGroup + '.interval']" class="mb-1"/>
                </div>
                <div class="">{{ autofillMessage }}</div>
                <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-slate-600 dark:text-slate-400">Saved</p>
                </Transition>
                <ButtonPrimary v-if="!form.recentlySuccessful && !form.hasErrors"
                               :class="{ 'opacity-25': (form.processing || !form.isDirty) }"
                               :disabled="form.processing || !form.isDirty">Save
                </ButtonPrimary>
            </div>

        </form>
    </section>
</template>
