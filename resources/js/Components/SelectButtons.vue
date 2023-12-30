<script setup>
import SelectButton from "@/Components/SelectButton.vue";

const props = defineProps({
    options: {
        type: Object,
    },
});
console.log(props.options)
</script>

<template>
    <SelectButton v-for="item in options" class="w-auto">
        <template #icon></template>
        {{ item.label }}
        <!--        <SpeciesIcon class="mx-1" :species="specie.value" />{{ specie.label }}-->
    </SelectButton>
    <!--            </div>-->
</template>
