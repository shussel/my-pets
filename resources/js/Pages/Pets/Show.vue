<script setup>
import {Head} from '@inertiajs/vue3';
import PetHeader from '@/Components/PetHeader.vue';
import SpeciesIcon from '@/Components/SpeciesIcon.vue';

const props = defineProps({
    pet: {
        type: Object,
        required: true,
    }
});
</script>

<template>
    <Head :title="pet.name" />

    <div class="flex flex-col justify-center items-center sm:flex-row sm:flex-wrap gap-6">
        <PetHeader :pet="pet">
            <div>{{ pet.sex }} <SpeciesIcon :species="pet.species" /></div>
            <div>Weight {{ pet.weight }} lbs</div>
            <div>Age {{ pet.age }}</div>
            <div>Born {{ new Date(pet.birth_date).toLocaleDateString('en-us', { year:"numeric", month:"short", day:"numeric"}) }}</div>
        </PetHeader>
    </div>
</template>
