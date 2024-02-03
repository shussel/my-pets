<script setup>
import PetCard from "@/Components/PetCard.vue";
import usePageTitle from "@/Composables/usePageTitle.js";

const props = defineProps({
    pet: {
        type: Object,
        required: true,
    }
});

usePageTitle("Pet Activity");

</script>

<template>

    <PetCard :pet="pet"/>

</template>
