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
        :type="type"
        class="w-[38px] h-[38px] inline-flex justify-center items-center p-1 m-[1px] bg-white border border-gray-300 rounded-md font-semibold text-center text-xl text-gray-700 lowercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
    >
        <slot />
    </button>
</template>
