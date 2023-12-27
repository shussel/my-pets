<script setup>
import PetHeader from '@/Components/PetHeader.vue';

const props = defineProps({
    pets: {
        type: Object,
    },
});
</script>

<template>
    <div class="flex flex-col justify-center items-center sm:flex-row sm:flex-wrap gap-6">
        <PetHeader v-for="pet in pets" :key="pet._id" :pet="pet" @click="$emit('nav', 'pets.show'); $emit('pet', pet._id)" />
    </div>
</template>
