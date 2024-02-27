<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Link
            :href="href"
            class="block w-full p-3 text-start leading-5 text-slate-800 dark:text-slate-300 hover:bg-lt/30 focus:outline-none focus:bg-lt/30 dark:focus:bg-lt/30 transition duration-150 ease-in-out"
    >
        <slot />
    </Link>
</template>
