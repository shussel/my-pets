<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    pet: {
        type: Object,
    },
});
</script>

<template>
    <div class="mt-4 flex justify-between">
        <SecondaryButton><font-awesome-icon icon="bowl-rice" /></SecondaryButton>
        <SecondaryButton><font-awesome-icon icon="tree" /></SecondaryButton>
        <SecondaryButton><font-awesome-icon icon="baseball" /></SecondaryButton>
        <SecondaryButton><font-awesome-icon icon="car-side" /></SecondaryButton>
        <SecondaryButton><font-awesome-icon icon="kit-medical" /></SecondaryButton>
    </div>
</template>
