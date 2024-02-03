<script setup>
import PetCard from "@/Components/PetCard.vue";
import ButtonPrimary from "@/Components/ButtonPrimary.vue";
import usePageTitle from "@/Composables/usePageTitle.js";
import useRoute from "@/Composables/useRoute.js";

const props = defineProps({
    pets: {
        type: Object,
    },
});

usePageTitle("Pets");

</script>

<template>
    <PetCard v-for="pet in pets" :key="pet._id" :pet="pet"/>

    <div class="sm:self-stretch sm:w-full text-center">
        <ButtonPrimary class="mb-4" @click.prevent="useRoute({ name: 'pets.create' })">Add a Pet</ButtonPrimary>
    </div>
</template>
