<script setup>
import { computed, ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import ButtonPrimary from "@/Components/ButtonPrimary.vue";
import InputButtons from "@/Components/InputButtons.vue";
import FAIcon from "@/Components/FAIcon.vue";
import useRoute from "@/Composables/useRoute.js";
import usePetAI from "@/Composables/usePetAI.js";

const props = defineProps({
    pet: {
        type: Object,
        required: true
    },
    behaviorOptions: {
        type: Object,
        default: {}
    },
});

const settingGroup = "behavior";

const suggestValues = ref(false);

const autofillMessage = computed(() => {
    return suggestValues.value ? "Suggested Values - Edit or" : null;
});

const settings = ref(props.pet?.settings?.[settingGroup] || {});

const form = useForm({
    [settingGroup]: {
        activity: settings.value.activity,
        sociability: settings.value.sociability,
        intelligence: settings.value.intelligence,
        noise: settings.value.noise
    }
});

const isSavable = computed(() => {
    return form.isDirty && (form[settingGroup].activity || form[settingGroup].sociability || form[settingGroup].intelligence || form[settingGroup].noise);
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
            <FAIcon class="text-2xl" name="brain"/>
            Behavior
        </h2>

        <form class="border px-4 pb-1 pt-5 -mt-3 space-y-2 lg:p-6 lg:pb-2" @submit.prevent="saveSettings">

            <div class="flex flex-wrap items-center gap-2">

                <div class="grow min-w-1/2 mb-2">
                    <InputLabel for="activity" value="Activity Level"/>
                    <InputButtons id="activity" v-model="form[settingGroup].activity"
                                  :options="behaviorOptions.activity" class="gap-2"/>
                </div>

                <div class="grow min-w-1/2 mb-2">
                    <InputLabel for="sociability" value="Sociability"/>
                    <InputButtons id="sociability" v-model="form[settingGroup].sociability"
                                  :options="behaviorOptions.sociability" class="gap-2"/>
                </div>

            </div>
            <div class="flex flex-wrap items-center gap-2">

                <div class="grow min-w-1/2 mb-2">
                    <InputLabel for="intelligence" value="Intelligence"/>
                    <InputButtons id="intelligence" v-model="form[settingGroup].intelligence"
                                  :options="behaviorOptions.intelligence" class="gap-2"/>
                </div>

                <div class="grow min-w-1/2 mb-2">
                    <InputLabel for="noise" value="Noise"/>
                    <InputButtons id="noise" v-model="form[settingGroup].noise" :options="behaviorOptions.noise"
                                  class="gap-2"/>
                </div>

            </div>

            <div v-show="isSavable || form.recentlySuccessful || form.hasErrors"
                 class="text-center pb-2 flex justify-center items-center gap-3 font-medium text-slate-400">
                <div>
                    <InputError :message="form.errors[settingGroup + '.activity']" class="mb-1"/>
                    <InputError :message="form.errors[settingGroup + '.sociability']" class="mb-1"/>
                    <InputError :message="form.errors[settingGroup + '.intelligence']" class="mb-1"/>
                    <InputError :message="form.errors[settingGroup + '.noise']" class="mb-1"/>
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
