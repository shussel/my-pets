<script setup>
const props = defineProps({
    message: {
        type: Object,
        required: true,
    }
});

</script>

<template>
    <div v-if="message" class="bubble p-3 ml-5 mt-2 text-gray-800 bg-blue-100 relative text-lg text-center rounded-lg">
        {{ message }}
    </div>
</template>

<style scoped>

.bubble:after {
    content: '';
    position: absolute;
    display: block;
    width: 0;
    z-index: 1;
    border-style: solid;
    border-color: transparent #dbe9fe;
    border-width: 9px 22px 9px 0;
    top: 51%;
    left: -20px;
    margin-top: -10px;
}

</style>
