<script setup>
import { computed, ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import ButtonPrimary from "@/Components/ButtonPrimary.vue";
import InputText from "@/Components/InputText.vue";
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

const settingGroup = "food";

const suggestValues = ref(props.meta?.feed?.length === 1);

const autofillMessage = computed(() => {
    return suggestValues.value ? "Suggested Values - Edit or" : null;
});

const settings = ref(props.pet?.settings?.[settingGroup] || {});

const form = useForm({
    [settingGroup]: {
        feed: settings.value.feed,
        time_1: settings.value.time_1,
        time_2: settings.value.time_2,
        interval: settings.value.interval
    }
});

// suggest times based on feed setting
watch(() => form[settingGroup].feed, () => {

    switch (form[settingGroup].feed) {
        case "once":
            if (!form[settingGroup].time_1 || form[settingGroup].time_1 === "00:00") {
                form[settingGroup].time_1 = "07:00";
                suggestValues.value = true;
            }
            delete form[settingGroup].time_2;
            delete form[settingGroup].interval;
            break;
        case "twice":
            if (!form[settingGroup].time_1 || form[settingGroup].time_1 === "00:00") {
                form[settingGroup].time_1 = "07:00";
                suggestValues.value = true;
            }
            if (!form[settingGroup].time_2 || form[settingGroup].time_2 === "00:00") {
                form[settingGroup].time_2 = "19:00";
                suggestValues.value = true;
            }
            delete form[settingGroup].interval;
            break;
        case "multi":
            form[settingGroup].time_1 = "00:00";
            form[settingGroup].time_2 = "23:59";
            form[settingGroup].interval = 3;
            suggestValues.value = true;
            break;
        case "free":
            delete form[settingGroup].time_1;
            delete form[settingGroup].time_2;
            delete form[settingGroup].interval;
            break;
        default:
    }
});

const timeTitle1 = computed(() => {
    return form[settingGroup].feed === "once" ? "Feed Time" : (form[settingGroup].feed === "twice" ? "Feed Time 1" : (form[settingGroup].feed === "multi" ? "Starting at" : ""));
});

const timeTitle2 = computed(() => {
    return form[settingGroup].feed === "twice" ? "Feed Time 2" : (form[settingGroup].feed === "multi" ? "Ending at" : "");
});

const isSavable = computed(() => {
    return form.isDirty && (form[settingGroup].feed === "free" ||
        (form[settingGroup].feed === "once" && form[settingGroup].time_1) ||
        (form[settingGroup].feed === "twice" && form[settingGroup].time_1 && form[settingGroup].time_2) ||
        (form[settingGroup].feed === "multi" && form[settingGroup].time_1 && form[settingGroup].time_2 && form[settingGroup].interval)
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
            <FAIcon class="text-2xl" name="food"/>
            Food
        </h2>

        <form class="border px-4 pb-1 pt-5 -mt-3 space-y-2 lg:p-6 lg:pb-2" @submit.prevent="saveSettings">

            <div class="flex flex-wrap items-center gap-2">

                <div class="grow min-w-1/2 mb-2">
                    <InputLabel for="feed" value="Daily Feedings"/>
                    <InputButtons id="feed" v-model="form[settingGroup].feed" :options="meta.feed" class="gap-2"/>
                </div>

                <div v-if="form[settingGroup].feed === 'multi'" class="min-w-1/2 mb-2">
                    <div class="mt-4 flex gap-2 items-center">
                        <div class="ml-2">every</div>
                        <div>
                            <InputText
                                    id="interval"
                                    v-model="form[settingGroup].interval"
                                    autocomplete="feed-time-1"
                                    class="block w-[55px] mt-1"
                                    max="8"
                                    min="1"
                                    type="number"
                            />
                        </div>
                        <div>hours</div>
                    </div>
                </div>

                <div v-if="form[settingGroup].feed && form[settingGroup].feed !== 'free'"
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
                    <div v-if="form[settingGroup].feed && (form[settingGroup].feed === 'twice' || form[settingGroup].feed === 'multi')"
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
                    <InputError :message="form.errors[settingGroup + '.feed']" class="mb-1"/>
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
