<script setup>
defineProps({
    selected: {
        type: Boolean,
        default: false,
    },
    selection: {
        type: String,
    }
});
</script>

<template>
    <button
            v-if="selected"
            class="flex justify-between w-full px-3 leading-6 lowercase flex items-center py-2 bg-white border border-gray-300 rounded-md text-center shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
    >
        <span>
            <slot/>
        </span>
        <span>></span>
    </button>
    <button
            v-else-if="!selection"
            class="px-2 bg-gray-100 leading-6 lowercase inline-flex justify-center items-center py-2 border border-gray-300 rounded-md text-center shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
    >
        <slot/>
    </button>
</template>
