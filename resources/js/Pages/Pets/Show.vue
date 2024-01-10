<script setup>
import PetHeader from "@/Components/PetHeader.vue";
import ButtonDefault from "@/Components/ButtonDefault.vue";

const props = defineProps({
    pet: {
        type: Object,
        required: true,
    }
});

const emit = defineEmits(["nav"]);

</script>

<template>
    <PetHeader :pet="pet">
        <div>{{ pet.sex }}</div>
        <div>Weight {{ pet.weight }} lbs</div>
        <div>Age {{ pet.age }}</div>
        <div>Born {{
                new Date(pet.birth_date).toLocaleDateString("en-us", {
                    year: "numeric",
                    month: "short",
                    day: "numeric"
                })
            }}
        </div>
    </PetHeader>
    <div class="sm:self-stretch sm:w-full text-center">
        <ButtonDefault :class="{ 'opacity-25': disableButtons }" :disabled="disableButtons" class="m-2"
                       @click="$emit('nav', 'pets.edit', pet._id)">Edit Pet
        </ButtonDefault>
    </div>
</template>
