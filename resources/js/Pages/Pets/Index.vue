<script setup>
import PetHeader from "@/Components/PetHeader.vue";
import ButtonPrimary from "@/Components/ButtonPrimary.vue";
import SpeechBubble from "@/Components/SpeechBubble.vue";
import FAIcon from "@/Components/FAIcon.vue";

const props = defineProps({
    data: {
        type: Object,
    },
});
</script>

<template>
    <PetHeader v-for="pet in data" :key="pet._id" :pet="pet" @click="$emit('nav', 'pets.show', pet._id);">
        <div>
            <h2>
                <FAIcon :name="pet.species" class="dark:text-slate-400"/>
                {{ pet.name }}
            </h2>
            <SpeechBubble :message="pet.message"/>
        </div>
    </PetHeader>
    <div class="sm:self-stretch sm:w-full text-center">
        <ButtonPrimary class="mb-4" @click="$emit('nav', 'pets.create')">Add a Pet</ButtonPrimary>
    </div>
</template>
