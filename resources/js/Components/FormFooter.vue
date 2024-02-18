<script setup>
import InputError from "@/Components/InputError.vue";
import ButtonPrimary from "@/Components/ButtonPrimary.vue";

const props = defineProps({
    form: {
        type: Object,
        required: true
    },
    isSavable: {
        type: Boolean,
        required: true
    },
    message: {
        type: String,
        required: true
    },
});
</script>

<template>
    <div v-show="isSavable || form.recentlySuccessful || form.hasErrors"
         class="text-center pb-2 flex justify-center items-center gap-3 font-medium text-slate-400">
        <div>
            <InputError v-for="error in form.errors" :message="error" class="mb-1"/>
        </div>
        <div class="">{{ message }}</div>
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
</template>