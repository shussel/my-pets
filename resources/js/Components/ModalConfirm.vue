<script setup>
import Modal from "@/Components/Modal.vue";
import ButtonPrimary from "@/Components/ButtonPrimary.vue";
import ButtonDefault from "@/Components/ButtonDefault.vue";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["confirm", "closeModal"]);
</script>

<template>
    <Modal :paw="true" :show="show" maxWidth="sq" @close="emit('closeModal')">
        <div class="pt-2 h-[225px] flex flex-col justify-between">
            <p class="my-auto flex items-center text-center text-lg font-medium text-slate-900 dark:text-slate-100">
                <slot/>
            </p>
            <div v-if="show" class="flex justify-center gap-3">
                <ButtonDefault @click="emit('confirm');">Yes</ButtonDefault>
                <ButtonPrimary autofocus @click="emit('closeModal')">No</ButtonPrimary>
            </div>
        </div>
    </Modal>
</template>
