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
    identityOptions: {
        type: Array,
        default: []
    },
});

const settingGroup = "identity";

const suggestValues = ref(props.identityOptions.length === 1);

const autofillMessage = computed(() => {
    return suggestValues.value ? "Suggested Values - Edit or" : null;
});

const settings = ref(props.pet?.settings?.[settingGroup] || {});

const form = useForm({
    [settingGroup]: {
        collar: settings.value.collar,
        chipped: settings.value.chipped,
        registry: settings.value.registry,
        chip_id: settings.value.chip_id,
        marks: settings.value.marks,
    }
});

const showCollar = computed(() => {
    return ["dog", "cat"].includes(props.pet.species);
});

const showChip = computed(() => {
    return ["dog", "cat", "bird", "horse", "reptile"].includes(props.pet.species);
});

const showChipDetails = computed(() => {
    return form[settingGroup].chipped;
});

// update chipped options
watch(() => form[settingGroup].chipped, () => {
    if (!form[settingGroup].chipped) {
        delete form[settingGroup].registry;
        delete form[settingGroup].chip_id;
    } else if (showChip.value) {
        form[settingGroup].registry = null;
        form[settingGroup].chip_id = null;
    }
});

const isSavable = computed(() => {
    return form.isDirty && (
        form[settingGroup].collar ||
        form[settingGroup].chipped ||
        form[settingGroup].marks
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
            <FAIcon class="text-2xl" name="identity"/>
            Identity
        </h2>

        <form class="border px-4 pb-1 pt-5 -mt-3 space-y-2 lg:p-6 lg:pb-2" @submit.prevent="saveSettings">

            <div v-if="showCollar || showChip" class="flex flex-wrap justify-start items-center gap-4">
                <div v-if="showCollar" class="grow min-w-1/2 mb-2">
                    <InputLabel for="collar" value="Collar Color"/>
                    <InputButtons id="collar" v-model="form[settingGroup].collar" :options="identityOptions.collar"
                                  class="gap-1"/>
                </div>

                <div v-if="showChip" class="min-w-1/2 mb-2 text-center">
                    <InputCheckbox id="chipped" v-model="form[settingGroup].chipped"
                                   :checked="form[settingGroup].chipped"/>
                    <InputLabel class="inline pl-2" for="chipped" value="Microchipped"/>
                </div>
            </div>

            <div v-if="showChipDetails" class="flex flex-wrap items-center gap-2">
                <div class="grow min-w-1/2 mb-2">
                    <InputLabel for="registry" value="Registry"/>
                    <InputButtons id="registry" v-model="form[settingGroup].registry"
                                  :options="identityOptions.registry" class="gap-2"/>
                </div>
                <div class="mb-2">
                    <InputLabel for="chip-id" value="Chip ID"/>
                    <InputText
                            id="chip-id"
                            v-model="form[settingGroup].chip_id"
                            autocomplete="no"
                            class="block w-full mt-1"
                            type="text"
                    />
                </div>
            </div>

            <div>
                <InputLabel for="marks" value="Distinctive Marks"/>
                <InputText
                        id="marks"
                        v-model="form[settingGroup].marks"
                        autocomplete="no"
                        class="block w-full mt-1 mb-2"
                        type="text"
                />
            </div>

            <div v-show="isSavable || form.recentlySuccessful || form.hasErrors"
                 class="text-center pb-2 flex justify-center items-center gap-3 font-medium text-slate-400">
                <div>
                    <InputError :message="form.errors[settingGroup + '.collar']" class="mb-1"/>
                    <InputError :message="form.errors[settingGroup + '.chipped']" class="mb-1"/>
                    <InputError :message="form.errors[settingGroup + '.registry']" class="mb-1"/>
                    <InputError :message="form.errors[settingGroup + '.chip_id']" class="mb-1"/>
                    <InputError :message="form.errors[settingGroup + '.marks']" class="mb-1"/>
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
