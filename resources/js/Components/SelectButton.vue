<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        class="inline-flex justify-center items-center p-1 bg-white border border-gray-300 rounded-md font-semibold text-center text-gray-700 lowercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
    >
        <slot name="icon"/>
        <slot/>
    </button>
</template>
