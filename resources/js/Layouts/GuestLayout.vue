<script setup xmlns="http://www.w3.org/1999/html">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div
            class="min-h-screen flex flex-col w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
        >
            <div>
                <Link href="/">
                    <ApplicationLogo class="fill-current text-gray-500" />
                </Link>
            </div>
            <div
                class="relative flex flex-col sm:justify-center sm:items-center bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white"
            >
            <slot />
            </div>
        </div>
    </div>
</template>
