<script setup>
import { computed, ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import ButtonPrimary from "@/Components/ButtonPrimary.vue";
import InputText from "@/Components/InputText.vue";
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

const settings = ref(props.pet?.settings?.food || {});

const form = useForm({
    food: {
        feed: settings.value.feed,
        feed_time_1: settings.value.feed_time_1,
        feed_time_2: settings.value.feed_time_2,
        feed_interval: settings.value.feed_interval
    }
});

watch(() => form.food.feed, () => {

    switch (form.food.feed) {
        case "once":
            form.food.feed_time_1 = (form.food.feed_time_1 && form.food.feed_time_1 !== "00:00") ? form.food.feed_time_1 : "07:00";
            delete form.food.feed_time_2;
            delete form.food.feed_interval;
            break;
        case "twice":
            form.food.feed_time_1 = (form.food.feed_time_1 && form.food.feed_time_1 !== "00:00") ? form.food.feed_time_1 : "07:00";
            form.food.feed_time_2 = (form.food.feed_time_2 && form.food.feed_time_2 !== "23:59") ? form.food.feed_time_2 : "19:00";
            delete form.food.feed_interval;
            break;
        case "multi":
            form.food.feed_time_1 = "00:00";
            form.food.feed_time_2 = "23:59";
            form.food.feed_interval = 3;
            break;
        case "free":
            delete form.food.feed_time_1;
            delete form.food.feed_time_2;
            delete form.food.feed_interval;
            break;
        default:
    }
});

const feed_time_1_title = computed(() => {
    return form.food.feed === "once" ? "Feed Time" : (form.food.feed === "twice" ? "Feed Time 1" : (form.food.feed === "multi" ? "Starting at" : ""));
});

const feed_time_2_title = computed(() => {
    return form.food.feed === "twice" ? "Feed Time 2" : (form.food.feed === "multi" ? "Ending at" : "");
});

const isSavable = computed(() => {
    return form.isDirty && (form.food.feed === "free" ||
        (form.food.feed === "once" && form.food.feed_time_1) ||
        (form.food.feed === "twice" && form.food.feed_time_1 && form.food.feed_time_2) ||
        (form.food.feed === "multi" && form.food.feed_time_1 && form.food.feed_time_2 && form.food.feed_interval)
    );
});

const saveSettings = () => {
    form.patch(route("pets.saveSettings", props.pet._id), {
        onSuccess: () => {
            usePetAI(props.pet, { name: "food" });
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
            <FAIcon class="text-2xl" name="food"/>
            Food
        </h2>

        <form class="border px-4 pb-1 pt-5 -mt-3 space-y-2 lg:p-6 lg:pb-2" @submit.prevent="saveSettings">

            <div class="flex flex-wrap items-center gap-2">

                <div class="grow min-w-1/2 mb-2">
                    <InputLabel for="feed" value="Daily Feedings"/>
                    <InputButtons id="feed" v-model="form.food.feed" :options="meta.feed" class="gap-2"/>
                </div>

                <div v-if="form.food.feed === 'multi'" class="min-w-1/2 mb-2">
                    <div class="mt-4 flex gap-2 items-center">
                        <div class="ml-2">every</div>
                        <div>
                            <InputText
                                    id="feed_interval"
                                    v-model="form.food.feed_interval"
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

                <div v-if="form.food.feed && form.food.feed !== 'free'"
                     class="grow min-w-1/2 mb-2 flex flex-wrap items-center gap-2">
                    <div class="grow min-w-1/2">
                        <InputLabel :value="feed_time_1_title" for="feed_time_1"/>
                        <InputText
                                id="feed_time_1"
                                v-model="form.food.feed_time_1"
                                autocomplete="feed-time-1"
                                class="block w-full mt-1"
                                type="time"
                        />
                    </div>
                    <div v-if="form.food.feed && (form.food.feed === 'twice' || form.food.feed === 'multi')"
                         class="grow min-w-1/2">
                        <InputLabel :value="feed_time_2_title" for="feed_time_2"/>
                        <InputText
                                id="feed_time_2"
                                v-model="form.food.feed_time_2"
                                autocomplete="feed-time-2"
                                class="block w-full mt-1"
                                type="time"
                        />
                    </div>
                </div>
            </div>

            <div>
                <InputError :message="form.errors.feed" class="mt-1"/>
                <InputError :message="form.errors.feed_time_1" class="mt-1"/>
                <InputError :message="form.errors.feed_time_2" class="mt-1"/>
            </div>

            <div v-show="isSavable || form.recentlySuccessful" class="text-center mb-3">
                <ButtonPrimary v-if="!form.recentlySuccessful"
                               :class="{ 'opacity-25': (form.processing || !form.isDirty) }"
                               :disabled="form.processing || !form.isDirty">Save
                </ButtonPrimary>

                <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-slate-600">Saved</p>
                </Transition>
            </div>

        </form>
    </section>
</template>
