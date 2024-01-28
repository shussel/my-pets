<script setup>
import Card from "@/Components/Card.vue";
import PetImage from "@/Components/PetImage.vue";
import PetButtons from "@/Components/PetButtons.vue";
import ButtonPrimary from "@/Components/ButtonPrimary.vue";
import PetStatus from "@/Components/PetStatus.vue";
import FAIcon from "@/Components/FAIcon.vue";
import usePageTitle from "@/Composables/usePageTitle.js";

const props = defineProps({
    pets: {
        type: Object,
    },
});

usePageTitle("Pets");

</script>

<template>
    <Card v-for="pet in pets" :key="pet._id" :pet="pet"
          @click="$emit('nav', { name: 'pets.show', params: pet._id });">
        <div class="flex justify-start gap-2">
            <PetImage :pet="pet"/>
            <div class="grow">
                <h2>
                    <FAIcon :name="pet.species" class="dark:text-slate-400"/>
                    {{ pet.name }}
                </h2>
                <PetStatus :pet="pet"/>
            </div>
        </div>
        <PetButtons :pet="pet"/>
    </Card>
    <div class="sm:self-stretch sm:w-full text-center">
        <ButtonPrimary class="mb-4" @click="$emit('nav', { name: 'pets.create' })">Add a Pet</ButtonPrimary>
    </div>
</template>
