<script setup>
const props = defineProps({
    message: {
        type: Object,
        required: true,
    }
});

</script>

<template>
    <div v-if="message" class="bubble p-3 ml-5 mt-2 relative
    rounded-2xl
    text-slate-700 text-lg text-center font-medium tracking-wide
        dark:text-slate-100
    bg-blue-100
        dark:bg-blue-300/60
    after:border-x-blue-100 after:border-y-transparent
        dark:after:border-x-blue-300/60 after:absolute after:block after:w-0 after:-mt-2">
        {{ message }}
    </div>
</template>

<style scoped>

.bubble:after {
    content: '';
    z-index: 50;
    border-style: solid;
    border-width: 9px 24px 9px 0;
    top: 40%;
    left: -24px;
}
</style>
