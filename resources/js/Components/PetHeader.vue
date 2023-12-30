<script setup>
import PetButtons from '@/Components/PetButtons.vue';
import PetImage from '@/Components/PetImage.vue';

const props = defineProps({
    pet: {
        type: Object,
    },
});
</script>

<template>
    <div class="w-[325px] p-2 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <div class="flex justify-start gap-2">
            <PetImage :pet="pet"/>
            <div>
                <slot/>
            </div>
        </div>
        <PetButtons :pet="pet"/>
    </div>
</template>
