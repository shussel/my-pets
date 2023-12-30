<script setup>
import PetHeader from '@/Components/PetHeader.vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import {useForm} from "@inertiajs/vue3";
import {defineEmits, nextTick, ref} from "vue";

const props = defineProps({
    pet: {
        type: Object,
        required: true,
    }
});

const deleteForm = useForm({
    _id: '',
});

const emit = defineEmits(['nav']);

const confirmPetDeletion = () => {
    confirmingPetDeletion.value = true;
    // nextTick(() => passwordInput.value.focus());
};
const confirmingPetDeletion = ref(false);

function deletePet(petId) {
    closeModal();
    deleteForm.delete(route('pets.destroy', petId), {
        onSuccess: () => {
            emit('nav', 'pets.index')
        },
    });
}

const closeModal = () => {
    confirmingPetDeletion.value = false;

    deleteForm.reset();
};
</script>

<template>
    <div class="flex flex-col justify-center items-center sm:flex-row sm:flex-wrap gap-6">
        <PetHeader :pet="pet">
            <div>{{ pet.sex }}</div>
            <div>Weight {{ pet.weight }} lbs</div>
            <div>Age {{ pet.age }}</div>
            <div>Born {{
                    new Date(pet.birth_date).toLocaleDateString('en-us', {
                        year: "numeric",
                        month: "short",
                        day: "numeric"
                    })
                }}
            </div>
        </PetHeader>
        <div class="sm:self-stretch sm:w-full text-center">
            <PrimaryButton class="m-2" @click="$emit('nav', 'pets.edit', pet._id)">Edit Pet</PrimaryButton>
            <PrimaryButton class="m-2" @click="confirmPetDeletion">Delete Pet</PrimaryButton>
        </div>
    </div>

    <Modal :show="confirmingPetDeletion" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Are you sure you want to delete this pet?
            </h2>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal"> Cancel</SecondaryButton>

                <DangerButton
                    class="ms-3"
                    :class="{ 'opacity-25': deleteForm.processing }"
                    :disabled="deleteForm.processing"
                    @click="deletePet(pet._id)"
                >
                    Delete Pet
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>
