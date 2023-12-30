<script setup>
import SpeciesIcon from '@/Components/SpeciesIcon.vue';

const props = defineProps({
    pet: {
        type: Object,
    },
});
</script>

<template>
    <img v-if="pet.image" class="w-[100px] h-[100px] rounded-full" :src="pet.image + '?p=' + pet._id" :alt="pet.name"/>
    <SpeciesIcon v-else :species="pet.species" class="bg-blue-100 text-white w-[76px] h-[76px] rounded-full p-3"/>
</template>
