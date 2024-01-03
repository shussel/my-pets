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
        class="flex justify-between w-full mt-1 px-3 py-2 bg-white border border-gray-300 rounded-md leading-6 text-center text-gray-700 lowercase shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
    >
        <div>
            <slot/>
        </div>
        <div class="">></div>
    </button>
    <button
        v-else-if="!selection"
        class="px-2 py-2 mt-1 bg-gray-100 border border-gray-300 rounded-md text-center text-gray-700 leading-6 lowercase shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
    >
        <slot/>
    </button>
</template>
