<script setup>
import { computed, ref, toRaw, watch, onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import ButtonPrimary from "@/Components/ButtonPrimary.vue";
import InputText from "@/Components/InputText.vue";
import InputCheckbox from "@/Components/InputCheckbox.vue";
import InputButtons from "@/Components/InputButtons.vue";
import useRoute from "@/Composables/useRoute.js";
import FAIcon from "@/Components/FAIcon.vue";
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

const settings = ref(props.pet?.settings?.poop || {});

const form = useForm({
    poop: {
        location: settings.value.location,
        poop_time_1: settings.value.poop_time_1,
        poop_time_2: settings.value.poop_time_2,
        clean_interval: settings.value.clean_interval,
        pet_door: settings.value.pet_door
    }
});

const poopOptions = computed(() => {
    return toRaw(props.meta.species).filter(function(specie) {
        return specie.value === props.pet.species;
    })[0]["poop"];
});

const autofillMessage = ref(null);

watch(() => form.poop.location, () => {
    let suggest = false;

    switch (form.poop.location) {
        case "walks":
            delete form.poop.pet_door;
        case "yard":
        case "pasture":
            if (!form.poop.poop_time_1 || form.poop.poop_time_1 === "00:00") {
                form.poop.poop_time_1 = "07:00";
                suggest = true;
            }
            if (!form.poop.poop_time_2 || form.poop.poop_time_2 === "00:00") {
                form.poop.poop_time_2 = "19:00";
                suggest = true;
            }
            delete form.poop.clean_interval;
            break;
        case "cage":
        case "litterbox":
        case "kennel":
            if (!form.poop.clean_interval) {
                form.poop.clean_interval = 2;
                suggest = true;
            }
            delete form.poop.poop_time_1;
            delete form.poop.poop_time_2;
            delete form.poop.pet_door;
            break;
        case "stable":
        case "tank":
            if (!form.poop.clean_interval) {
                form.poop.clean_interval = 7;
                suggest = true;
            }
            delete form.poop.poop_time_1;
            delete form.poop.poop_time_2;
            delete form.poop.pet_door;
            break;
        case "aviary":
        case "pond":
            delete form.poop.poop_time_1;
            delete form.poop.poop_time_2;
            delete form.poop.clean_interval;
            delete form.poop.pet_door;
            break;
        default:
    }

    if (suggest) {
        autofillMessage.value = "Suggested Values - Edit or";
    } else {
        autofillMessage.value = null;
    }
});

watch(() => form.poop.pet_door, () => {
    if (form.poop.pet_door) {
        delete form.poop.poop_time_1;
        delete form.poop.poop_time_2;
    }
});

const poop_time_1_title = computed(() => {
    switch (form.poop.location) {
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

const poop_time_2_title = computed(() => {
    switch (form.poop.location) {
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

const cleanSchedule = computed(() => {
    return ["kennel", "litterbox", "tank", "cage", "stable"].includes(form.poop.location);
});

const showTimes = computed(() => {
    return ["yard", "walks", "pasture"].includes(form.poop.location) && !form.poop.pet_door;
});

const petDoor = computed(() => {
    return ["yard", "pasture"].includes(form.poop.location);
});

const isSavable = computed(() => {
    return form.isDirty && (form.poop.location === "yard" ||
        form.poop.location === "pond" ||
        form.poop.location === "pasture" ||
        form.poop.location === "aviary" ||
        ((form.poop.location === "litterbox" || form.poop.location === "kennel" || form.poop.location === "cage" || form.poop.location === "tank" || form.poop.location === "stable") && form.poop.clean_interval) ||
        (form.poop.location === "walks" && form.poop.poop_time_1)
    );
});

const saveSettings = () => {
    autofillMessage.value = null;
    form.patch(route("pets.saveSettings", props.pet._id), {
        preserveScroll: true,
        onSuccess: () => {
            usePetAI(props.pet, { name: "poop" });
            useRoute({ name: "pets.settings", params: props.pet._id });
        },
        onError: () => {
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
                    <InputLabel for="location" value="Where does pet go?"/>
                    <InputButtons id="location" v-model="form.poop.location" :options="poopOptions" class="gap-2"/>
                </div>

                <div v-if="cleanSchedule" class="min-w-1/2 mb-2">
                    <InputLabel
                            :value="'Clean ' + (form.poop.location && form.poop.location[0].toUpperCase() + form.poop.location.slice(1))"
                            for="clean_interval"/>
                    <div class="flex gap-2 items-center">
                        <div class="ml-2">every</div>
                        <div>
                            <InputText
                                    id="clean_interval"
                                    v-model="form.poop.clean_interval"
                                    autocomplete="poop-time-1"
                                    class="block w-[60px] mt-1"
                                    max="30"
                                    min="1"
                                    type="number"
                            />
                        </div>
                        <div>days</div>
                    </div>
                </div>

                <div v-if="petDoor" class="grow min-w-1/2 mb-2 mt-4 text-center">
                    <InputCheckbox id="petDoor" v-model="form.poop.pet_door" :checked="form.poop.pet_door"/>
                    <InputLabel class="inline pl-2" for="petDoor" value="Has Pet Door"/>
                </div>

                <div v-if="showTimes"
                     class="grow min-w-1/2 mb-2 flex flex-wrap items-center gap-2">
                    <div class="grow min-w-1/2">
                        <InputLabel :value="poop_time_1_title" for="poop_time_1"/>
                        <InputText
                                id="poop_time_1"
                                v-model="form.poop.poop_time_1"
                                autocomplete="poop-time-1"
                                class="block w-full mt-1"
                                type="time"
                        />
                    </div>
                    <div v-if="showTimes"
                         class="grow min-w-1/2">
                        <InputLabel :value="poop_time_2_title" for="poop_time_2"/>
                        <InputText
                                id="poop_time_2"
                                v-model="form.poop.poop_time_2"
                                autocomplete="poop-time-2"
                                class="block w-full mt-1"
                                type="time"
                        />
                    </div>
                </div>
            </div>

            <div>
                <InputError :message="form.errors.location" class="mt-1"/>
                <InputError :message="form.errors.poop_time_1" class="mt-1"/>
                <InputError :message="form.errors.poop_time_2" class="mt-1"/>
            </div>

            <div v-show="isSavable || form.recentlySuccessful"
                 class="text-center pb-2 flex justify-center items-center gap-3 font-medium text-slate-400">
                <div class="">{{ autofillMessage }}</div>
                <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-slate-600 dark:text-slate-400">Saved</p>
                </Transition>
                <ButtonPrimary v-if="!form.recentlySuccessful"
                               :class="{ 'opacity-25': (form.processing || !form.isDirty) }"
                               :disabled="form.processing || !form.isDirty">Save
                </ButtonPrimary>
            </div>

        </form>
    </section>
</template>
