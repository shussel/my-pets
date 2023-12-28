<script setup>
import PetButtons from '@/Components/PetButtons.vue';

const props = defineProps({
    pet: {
        type: Object,
    },
});
</script>

<template>
    <div class="w-[325px] p-2 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <div class="flex justify-start gap-2">
            <img class="w-[100px] h-[100px] rounded-full" :src="pet.image + '?t=' + Math.ceil(Math.random()*10000)" :alt="pet.name" />
            <div>
                <slot/>
            </div>
        </div>
        <PetButtons :pet="pet" />
    </div>
</template>
