<script setup>
import PetHeader from "@/Components/PetHeader.vue";
import ButtonDefault from "@/Components/ButtonDefault.vue";

const props = defineProps({
    currentData: {
        type: Object,
        required: true,
    }
});

const emit = defineEmits(["nav"]);

</script>

<template>
    <PetHeader :pet="currentData">
        <div>{{ currentData.sex }}</div>
        <div>Weight {{ currentData.weight }} lbs</div>
        <div>Age {{ currentData.age }}</div>
        <div>Born {{
                new Date(currentData.birth_date).toLocaleDateString("en-us", {
                    year: "numeric",
                    month: "short",
                    day: "numeric"
                })
            }}
        </div>
    </PetHeader>
    <div class="sm:self-stretch sm:w-full text-center">
        <ButtonDefault :class="{ 'opacity-25': disableButtons }" :disabled="disableButtons" class="m-2"
                       @click="$emit('nav', { name: 'pets.edit', params: currentData._id})">Edit Pet
        </ButtonDefault>
    </div>
</template>
