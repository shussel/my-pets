<script setup>
import FAIcon from "@/Components/FAIcon.vue";

const props = defineProps({
    pet: {
        type: Object,
    },
});
</script>

<template>
    <div class="w-[100px] h-[100px] aspect-square rounded-full bg-lt/50 hover:bg-lt/80">
        <img v-if="pet.avatar" :alt="pet.name" :src="'/storage/' + pet.avatar" class="w-full h-auto rounded-full"/>
        <FAIcon v-else :name="pet.species" class="text-white dark:text-slate-700 w-3/4 h-3/4 p-[12%]"/>
    </div>
</template>
