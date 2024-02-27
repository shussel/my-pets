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
        ? "block w-full ps-8 pe-4 py-2 text-start text-lg font-bold text-slate-800 dark:text-slate-200 bg-lt/50 focus:text-slate-900 focus:bg-lt/50 dark:focus:text-slate-100 transition duration-150 ease-in-out border-b border-b-slate-200 dark:border-b-slate-500"
        : "block w-full ps-8 pe-4 py-2 text-start text-lg font-bold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-slate-100 hover:bg-lt/50 dark:hover:bg-lt/50 focus:text-slate-900 dark:focus:text-slate-100 focus:bg-lt/50 transition duration-150 ease-in-out border-b border-b-slate-200 dark:border-b-slate-500"
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
