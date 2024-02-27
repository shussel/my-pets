<script setup>
import ButtonDefault from "@/Components/ButtonDefault.vue";
</script>

<template>
    <ButtonDefault
            class="bg-red-600 border-transparent text-white hover:bg-red-500 active:bg-red-700 focus:ring-red-500"
    >
        <slot/>
    </ButtonDefault>
</template>
