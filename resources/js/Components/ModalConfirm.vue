<script setup>
import {ref, watch} from "vue";
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
    <Modal :show="show" maxWidth="sq" @close="emit('closeModal')">
        <div class="p-6 grow h-[300px] flex flex-col justify-between gap-4">
            <p class="mt-6 grow flex items-center text-center text-lg font-medium text-slate-900 dark:text-slate-100">
                <slot/>
            </p>
            <div v-if="show" class="mb-12 flex justify-center gap-3">
                <ButtonDefault @click="emit('confirm');">Yes</ButtonDefault>
                <ButtonPrimary autofocus @click="emit('closeModal')">No</ButtonPrimary>
            </div>
        </div>
    </Modal>
</template>
