<script setup>
import PetHeader from '@/Components/PetHeader.vue';
import {toRaw, toRef} from "vue";

const props = defineProps({
    petId: {
        type: String,
    },
    pets: {
        type: Object,
    }
});

const pet = toRef(toRaw(props.pets).filter(function (pet) {
    return pet._id === props.petId;
})[0])
</script>

<template>
    <div class="flex flex-col justify-center items-center sm:flex-row sm:flex-wrap gap-6">
        <PetHeader :pet="pet">
            <div>{{ pet.sex }}</div>
            <div>{{ pet.weight }} lbs</div>
            <div>{{ pet.age }}</div>
            <div>{{ new Date(pet.birth_date).toLocaleDateString('en-us', { year:"numeric", month:"short", day:"numeric"}) }}</div>
        </PetHeader>
    </div>
</template>
