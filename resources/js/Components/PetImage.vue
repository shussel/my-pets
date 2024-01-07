<script setup>
import FAIcon from '@/Components/FAIcon.vue';

const props = defineProps({
    pet: {
        type: Object,
    },
});
</script>

<template>
  <div class="w-[100px] h-[100px] rounded-full bg-blue-100">
    <img v-if="pet.avatar" class="w-full h-auto rounded-full" :src="'/storage/' + pet.avatar" :alt="pet.name"/>
    <FAIcon v-else :name="pet.species" class="text-white w-3/4 h-3/4 p-[12%]"/>
  </div>
</template>
