<script setup>
import Card from "@/Components/Card.vue";
import PetImage from "@/Components/PetImage.vue";
import PetButtons from "@/Components/PetButtons.vue";
import PetStatus from "@/Components/PetStatus.vue";
import FAIcon from "@/Components/FAIcon.vue";
import useRoute from "@/Composables/useRoute.js";

const props = defineProps({
    pet: {
        type: Object,
        required: true
    },
});
</script>

<template>
    <Card>
        <div class="flex justify-start gap-2">
            <PetImage :pet="pet" @click="useRoute({ name: 'pets.show', params: pet._id })"/>
            <div class="grow">
                <div class="flex justify-between">
                    <h2 class="-ml-[20px] bg-transparent">
                        <FAIcon :name="pet.species" class="dark:text-slate-400"/>
                        {{ pet.name }}
                    </h2>
                    <FAIcon class="text-xl"
                            color="text-slate-300 hover:text-slate-400 dark:text-slate-500 dark:hover:text-slate-400"
                            name="gear" title="profile" @click="useRoute({ name: 'pets.settings', params: pet._id })"/>
                </div>
                <PetStatus :pet="pet"/>
            </div>
        </div>
        <PetButtons :pet="pet"/>
    </Card>

</template>
