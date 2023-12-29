<script setup>
import PetHeader from '@/Components/PetHeader.vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {useForm} from "@inertiajs/vue3";
import {defineEmits} from "vue";

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

function deletePet(petId) {
    deleteForm.delete(route('pets.destroy',petId), {
        onSuccess: () => { emit('nav', 'pets.index')},
    });
}
</script>

<template>
    <div class="flex flex-col justify-center items-center sm:flex-row sm:flex-wrap gap-6">
        <PetHeader :pet="pet">
            <div>{{ pet.sex }}</div>
            <div>Weight {{ pet.weight }} lbs</div>
            <div>Age {{ pet.age }}</div>
            <div>Born {{ new Date(pet.birth_date).toLocaleDateString('en-us', { year:"numeric", month:"short", day:"numeric"}) }}</div>
        </PetHeader>
        <div class="sm:self-stretch sm:w-full text-center">
            <PrimaryButton class="mb-4" @click="$emit('nav', 'pets.edit', pet._id)">Edit Pet</PrimaryButton>
            <PrimaryButton class="mb-4" @click="deletePet(pet._id)">Delete Pet</PrimaryButton>
        </div>
    </div>
</template>
