<script setup xmlns="http://www.w3.org/1999/html">
import {Link} from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
</script>

<template>
    <div class="min-h-screen flex flex-col justify-start items-center bg-white sm:bg-gray-100">
        <div
                class="sm:mt-8 flex flex-col justify-start items-stretch w-full sm:max-w-md px-8 py-8 bg-white sm:shadow-md overflow-hidden sm:rounded-lg"
        >
            <div class="mt-3 self-center">
                <Link href="/">
                    <ApplicationLogo class="fill-current text-gray-500"/>
                </Link>
            </div>
            <h1 class="self-center font-sans text-4xl antialiased font-semibold leading-tight tracking-normal text-inherit">
                MyPets</h1>
            <div class="self-center sm:text-lg mx-auto p-2">
                The app to help take care of your pets
            </div>
            <slot/>
        </div>
    </div>
</template>
