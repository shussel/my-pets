<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? "block w-full ps-4 pe-4 py-2 border-t-2 text-start text-lg font-bold text-indigo-700 bg-blue-200 focus:outline-none focus:text-indigo-800 focus:bg-blue-200 focus:border-blue-200 transition duration-150 ease-in-out"
        : "block w-full ps-4 pe-4 py-2 border-t-2 text-start text-lg font-bold text-gray-600 hover:text-gray-800 hover:bg-blue-100 hover:border-blue-100 focus:outline-none focus:text-gray-800 focus:bg-blue-200 focus:border-blue-200 transition duration-150 ease-in-out"
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
