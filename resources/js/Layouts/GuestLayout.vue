<script setup xmlns="http://www.w3.org/1999/html">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col justify-center items-center bg-gray-100">
        <div
            class="flex flex-col justify-start items-center w-full sm:max-w-md px-8 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg"
        >
            <div class="mt-3">
                <Link href="/">
                    <ApplicationLogo class="fill-current text-gray-500" />
                </Link>
            </div>
            <h1 class="font-sans text-4xl antialiased font-semibold leading-tight tracking-normal text-inherit">MyPets</h1>
            <slot />
        </div>
    </div>
</template>
