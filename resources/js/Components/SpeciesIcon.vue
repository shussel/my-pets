<template>
    <font-awesome-icon class="font-bold text-xl" :icon="speciesIcons[species]" />
</template>

<script setup>

const props = defineProps({
    species: {
        type: String,
    },
});

const speciesIcons = {
    dog: 'fa-dog',
    cat: 'fa-cat',
    bird: 'fa-crow',
    fish: 'fa-fish',
    reptile: 'fa-dragon',
}

</script>

