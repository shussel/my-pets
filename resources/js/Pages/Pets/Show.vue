<script setup>
import PetHeader from '@/Components/PetHeader.vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
  pet: {
    type: Object,
    required: true,
  }
});

const emit = defineEmits(['nav']);

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
      </div>
    </div>
</template>
