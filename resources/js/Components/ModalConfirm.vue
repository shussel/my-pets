<script setup>
import {ref, watch} from "vue";
import Modal from "@/Components/Modal.vue";
import ButtonPrimary from "@/Components/ButtonPrimary.vue";
import ButtonDefault from "@/Components/ButtonDefault.vue";

const props = defineProps({
    message: {
        type: Boolean,
        default: false,
    },
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
            <p class="mt-6 grow flex items-center text-center text-lg font-medium text-gray-900">
                <slot/>
            </p>

            <div class="mb-12 flex justify-center gap-3">
                <ButtonDefault autofocus @click="emit('closeModal')">No</ButtonDefault>
                <ButtonPrimary
                        @click="emit('confirm');"
                >
                    Yes
                </ButtonPrimary>
            </div>
        </div>
    </Modal>
</template>
