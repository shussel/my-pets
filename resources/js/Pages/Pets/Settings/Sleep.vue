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
    species: {
        type: Array,
        default: []
    },
});

const settingGroup = "sleep";

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
        assist: settings.value.assist || null,
    }
});

// suggest based on location
watch(() => form[settingGroup].location, () => {

    if (["inside", "crate", "kennel", "cage", "stable", "tank", "outside", "dog_house"].includes(form[settingGroup].location)) {
        if (form[settingGroup].assist || ["inside", "crate"].includes(form[settingGroup].location)) {
            if (!form[settingGroup].time_1) {
                form[settingGroup].time_1 = "22:00";
                suggestValues.value = true;
            }
            if (!form[settingGroup].time_2) {
                form[settingGroup].time_2 = "07:00";
                suggestValues.value = true;
            }
        } else {
            form[settingGroup].time_1 = null;
            form[settingGroup].time_2 = null;
            suggestValues.value = false;
        }
    } else if (["pond", "aviary", "pasture"].includes(form[settingGroup].location)) {
        form[settingGroup].time_1 = null;
        form[settingGroup].time_2 = null;
        form[settingGroup].assist = null;
        suggestValues.value = false;
    }
});

const assistSleep = computed(() => {
    return ["inside", "crate", "kennel", "dog_house", "outside", "cage", "stable", "tank"].includes(form[settingGroup].location);
});

// suggest based on assist
watch(() => form[settingGroup].assist, () => {

    if (form[settingGroup].assist || ["inside", "crate"].includes(form[settingGroup].location)) {
        if (!form[settingGroup].time_1) {
            form[settingGroup].time_1 = "22:00";
            suggestValues.value = true;
        }
        if (!form[settingGroup].time_2) {
            form[settingGroup].time_2 = "07:00";
            suggestValues.value = true;
        }
    } else {
        form[settingGroup].time_1 = null;
        form[settingGroup].time_2 = null;
        suggestValues.value = false;
    }

});

const assistTitle = computed(() => {
    switch (form[settingGroup].location) {
        case "inside":
        case "stable":
            return "Let In";
        case "crate":
            return "Put in Crate";
        case "kennel":
            return "Put in Kennel";
        case "dog_house":
        case "outside":
            return "Let Out";
        case "cage":
            return "Cover";
        case "tank":
            return "Turn Off Lights";
        default:
            return null;
    }
});

const showTimes = computed(() => {
    return ["inside", "crate", "kennel", "dog_house", "tank", "cage", "stable", "outside"].includes(form[settingGroup].location) && (form[settingGroup].assist || ["inside", "crate"].includes(form[settingGroup].location));
});

const timeTitle1 = computed(() => {

    if (form[settingGroup].assist) {
        switch (form[settingGroup].location) {
            case "outside":
            case "dog_house":
                return "Let Out At";
            case "inside":
            case "stable":
                return "Let In At";
            case "crate":
                return "Crate At";
            case "cage":
                return "Cover At";
            case "kennel":
                return "Kennel At";
            case "tank":
                return "Lights Off At";
            default:
                return null;
        }
    } else if (["inside", "crate"].includes(form[settingGroup].location)) {
        return "Sleep Time";
    }
});

const timeTitle2 = computed(() => {

    if (form[settingGroup].assist) {
        switch (form[settingGroup].location) {
            case "outside":
            case "dog_house":
                return "Let In At";
            case "inside":
            case "stable":
                return "Let Out At";
            case "crate":
                return "Uncrate At";
            case "cage":
                return "Uncover At";
            case "kennel":
                return "Unkennel At";
            case "tank":
                return "Lights On At";
            default:
                return null;
        }
    } else if (["inside", "crate"].includes(form[settingGroup].location)) {
        return "Wake Time";
    }
});

const isSavable = computed(() => {
    return form.isDirty && form[settingGroup].location;
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
            <FAIcon class="text-2xl" name="dark"/>
            Sleep
        </h2>

        <form class="border px-4 pb-1 pt-5 -mt-3 space-y-2 lg:p-6 lg:pb-2" @submit.prevent="saveSettings">

            <div class="flex flex-wrap items-center gap-2">

                <div class="grow min-w-1/2 mb-2">
                    <InputLabel for="location" value="Location"/>
                    <InputButtons id="location" v-model="form[settingGroup].location" :options="speciesOptions"
                                  class="gap-2"/>
                </div>

                <div v-if="assistSleep" class="grow min-w-1/2 mb-2 mt-4 text-center">
                    <InputCheckbox id="petDoor" v-model="form[settingGroup].assist"
                                   :checked="form[settingGroup].assist"/>
                    <InputLabel :value="assistTitle" class="inline pl-2" for="assist"/>
                </div>

                <div v-if="showTimes"
                     class="grow min-w-1/2 mb-2 flex flex-wrap items-center gap-2">
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
                        <InputLabel :value="timeTitle2" for="time-2"/>
                        <InputText
                                id="time-2"
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
