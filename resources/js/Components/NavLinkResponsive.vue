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
        ? "block w-full ps-4 pe-4 py-2 border-t-2 text-start text-lg font-bold text-lt/50 dark:text-lt bg-lt/50 dark:bg-blue-800 focus:outline-none focus:text-blue-800 focus:bg-lt/50 focus:border-lt/50 dark:focus:text-lt/50 dark:focus:bg-blue-800 dark:focus:border-blue-800 transition duration-150 ease-in-out"
        : "block w-full ps-4 pe-4 py-2 border-t-2 text-start text-lg font-bold text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-lt/50 dark:hover:bg-blue-900 hover:border-lt/50 dark:hover:border-blue-900 focus:outline-none focus:text-slate-800 dark:focus:text-slate-200 focus:bg-lt/50 dark:focus:bg-blue-800 focus:border-lt/50 dark:focus:border-blue-800 transition duration-150 ease-in-out"
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
