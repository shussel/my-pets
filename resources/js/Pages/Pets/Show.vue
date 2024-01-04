<script setup>
import {defineEmits, ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import PetHeader from '@/Components/PetHeader.vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import ModalConfirm from '@/Components/ModalConfirm.vue';

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
  disableButtons.value = true;
};
const confirmingPetDeletion = ref(false);
const disableButtons = ref(false);

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
        <PrimaryButton class="m-2" @click="$emit('nav', 'pets.edit', pet._id)" :class="{ 'opacity-25': disableButtons }"
                       :disabled="disableButtons">Edit Pet
        </PrimaryButton>
        <PrimaryButton class="m-2" @click="confirmPetDeletion" :class="{ 'opacity-25': disableButtons }"
                       :disabled="disableButtons">Delete Pet
        </PrimaryButton>
      </div>
    </div>

  <ModalConfirm :show="confirmingPetDeletion" @confirm="deletePet(pet._id)" @closeModal="closeModal">Are you sure you
    want to delete this pet?
  </ModalConfirm>
</template>
