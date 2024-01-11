<script setup>
import PetButtons from "@/Components/PetButtons.vue";
import PetImage from "@/Components/PetImage.vue";
import Card from "@/Components/Card.vue";

const props = defineProps({
    pet: {
        type: Object,
    },
});
</script>

<template>
    <Card>
        <div class="flex justify-start gap-2">
            <PetImage :pet="pet"/>
            <div class="grow">
                <slot/>
            </div>
        </div>
        <PetButtons :pet="pet"/>
    </Card>
</template>
