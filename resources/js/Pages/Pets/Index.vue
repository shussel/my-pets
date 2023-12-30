<script setup>
import PetHeader from '@/Components/PetHeader.vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import FAIcon from '@/Components/FAIcon.vue';

const props = defineProps({
    pets: {
        type: Object,
    },
});
</script>

<template>
    <div class="flex flex-col justify-center items-center sm:flex-row sm:flex-wrap gap-6">
        <PetHeader v-for="pet in pets" :key="pet._id" :pet="pet" @click="$emit('nav', 'pets.show', pet._id);">
            <div>
                <FAIcon :name="pet.species"/>
                {{ pet.name }}
            </div>
        </PetHeader>
        <div class="sm:self-stretch sm:w-full text-center">
            <PrimaryButton class="mb-4" @click="$emit('nav', 'pets.create')">Add a Pet</PrimaryButton>
        </div>
    </div>
</template>
