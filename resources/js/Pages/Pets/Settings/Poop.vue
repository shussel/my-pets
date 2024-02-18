<script setup>
import { computed, ref, toRaw, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import ButtonPrimary from "@/Components/ButtonPrimary.vue";
import InputText from "@/Components/InputText.vue";
import InputCheckbox from "@/Components/InputCheckbox.vue";
import InputButtons from "@/Components/InputButtons.vue";
import ButtonTime from "@/Components/ButtonTime.vue";
import FAIcon from "@/Components/FAIcon.vue";
import useRoute from "@/Composables/useRoute.js";
import usePetAI from "@/Composables/usePetAI.js";

const props = defineProps({
    pet: {
        type: Object,
        required: true
    },
    species: {
        type: Array,
        default: []
    },
});

const settingGroup = "poop";

const speciesOptions = computed(() => {
    return toRaw(props.species).filter(function(specie) {
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
        location: settings.value.location || null,
        time_1: settings.value.time_1 || null,
        time_2: settings.value.time_2 || null,
        interval: settings.value.interval || null,
        assist: settings.value.assist || null
    }
});

// suggest based on location
watch(() => form[settingGroup].location, () => {

    switch (form[settingGroup].location) {
        case "walks":
            if (!form[settingGroup].time_1) {
                form[settingGroup].time_1 = "08:00";
                suggestValues.value = true;
            }
            form[settingGroup].time_2 = null;
            form[settingGroup].interval = null;
            form[settingGroup].assist = null;
            break;
        case "yard":
        case "pasture":
            if (form[settingGroup].assist) {
                if (!form[settingGroup].time_1) {
                    form[settingGroup].time_1 = "07:00";
                    suggestValues.value = true;
                }
                if (!form[settingGroup].time_2) {
                    form[settingGroup].time_2 = "19:00";
                    suggestValues.value = true;
                }
            } else {
                form[settingGroup].time_1 = null;
                form[settingGroup].time_2 = null;
                suggestValues.value = false;
            }
            form[settingGroup].interval = null;
            break;
        case "cage":
        case "litterbox":
        case "kennel":
            if (!form[settingGroup].interval) {
                form[settingGroup].interval = 2;
                suggestValues.value = true;
            }
            form[settingGroup].time_1 = null;
            form[settingGroup].time_2 = null;
            form[settingGroup].assist = null;
            break;
        case "stable":
        case "tank":
            if (!form[settingGroup].interval) {
                form[settingGroup].interval = 7;
                suggestValues.value = true;
            }
            form[settingGroup].time_1 = null;
            form[settingGroup].time_2 = null;
            form[settingGroup].assist = null;
            break;
        case "aviary":
        case "pond":
            form[settingGroup].time_1 = null;
            form[settingGroup].time_2 = null;
            form[settingGroup].interval = null;
            form[settingGroup].assist = null;
            suggestValues.value = false;
            break;
        default:
    }
});

const showAssist = computed(() => {
    return ["yard", "pasture"].includes(form[settingGroup].location);
});

watch(() => form[settingGroup].assist, () => {

    if (["yard", "pasture"].includes(form[settingGroup].location)) {
        if (form[settingGroup].assist) {
            if (!form[settingGroup].time_1) {
                form[settingGroup].time_1 = "07:00";
                suggestValues.value = true;
            }
            if (!form[settingGroup].time_2) {
                form[settingGroup].time_2 = "19:00";
                suggestValues.value = true;
            }
        } else {
            form[settingGroup].time_1 = null;
            form[settingGroup].time_2 = null;
            suggestValues.value = false;
        }
    }
});

const assistTitle = computed(() => {
    return ["yard", "pasture"].includes(form[settingGroup].location) ? "Let Out" : null;
});

const cleanSchedule = computed(() => {
    return ["kennel", "litterbox", "tank", "cage", "stable"].includes(form[settingGroup].location);
});

const showTimes = computed(() => {
    return ["walks"].includes(form[settingGroup].location) ||
        (["pasture", "yard"].includes(form[settingGroup].location) && form[settingGroup].assist);
});

const timeTitle1 = computed(() => {
    return ["pasture", "yard"].includes(form[settingGroup].location) ? "Let Out Time" :
        (form[settingGroup].location === "walks" ? "Walk Time" + (form[settingGroup].time_2 ? " 1" : "") : null);
});

const timeTitle2 = computed(() => {
    return ["pasture", "yard"].includes(form[settingGroup].location) ? "Let In Time" :
        (form[settingGroup].location === "walks" ? "Walk Time 2" : null);
});

const isSavable = computed(() => {
    return form.isDirty && form[settingGroup].location &&
        (
            ["aviary", "pasture", "yard", "pond"].includes(form[settingGroup].location) ||
            (
                ["litterbox", "kennel", "cage", "tank", "stable"].includes(form[settingGroup].location) && form[settingGroup].interval
            ) ||
            (
                ["walks"].includes(form[settingGroup].location) && form[settingGroup].time_1
            )
        );
});

const saveSettings = () => {
    suggestValues.value = false;
    for (const field in form.data()[settingGroup]) {
        if (!form[settingGroup][field]) {
            delete form[settingGroup][field];
        }
    }
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
                                    autocomplete="off"
                                    class="block w-[60px] mt-1"
                                    max="90"
                                    min="0"
                                    type="number"
                            />
                        </div>
                        <div>days</div>
                    </div>
                </div>

                <div v-if="showAssist" class="grow min-w-1/2 mb-2 mt-4 text-center">
                    <InputCheckbox id="petDoor" v-model="form[settingGroup].assist"
                                   :checked="form[settingGroup].assist"/>
                    <InputLabel :value="assistTitle" class="inline pl-2" for="petDoor"/>
                </div>

                <div v-if="showTimes"
                     class="grow min-w-1/2 mb-2 flex flex-wrap items-end gap-2">
                    <div class="grow min-w-1/2">
                        <InputLabel :value="timeTitle1" for="time-1"/>
                        <InputText
                                id="time-1"
                                v-model="form[settingGroup].time_1"
                                autocomplete="time-1"
                                class="block w-full mt-1"
                                type="time"
                        />
                    </div>
                    <div v-if="showTimes"
                         class="grow min-w-1/2">
                        <div v-if="form[settingGroup].time_2">
                            <div class="flex justify-between items-end">
                                <InputLabel :value="timeTitle2" for="time-2"/>
                                <FAIcon v-if="form[settingGroup].location === 'walks'"
                                        class="text-base text-slate-700 dark:text-slate-300" name="delete"
                                        @click="form[settingGroup].time_2 = null"/>
                            </div>
                            <InputText
                                    id="time-2"
                                    v-model="form[settingGroup].time_2"
                                    autocomplete="time-2"
                                    class="block w-full mt-1"
                                    type="time"
                            />
                        </div>
                        <ButtonTime v-else @click.prevent="form[settingGroup].time_2 = '17:00'"/>
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
