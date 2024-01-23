<script setup>
import { Link } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import useSettings from "@/Composables/useSettings.js";
import useRoute from "@/Composables/useRoute.js";

const { settings, useDark } = useSettings();
</script>

<template>
    <div :class="{ dark: useDark }" :data-theme="settings.theme || 'default'">
        <div class="min-h-screen flex flex-col justify-start items-center dark:text-slate-300 bg-white sm:bg-slate-100 dark:bg-slate-700 dark:sm:bg-slate-800">
            <div
                    class="sm:mt-8 flex flex-col justify-start items-stretch w-full sm:max-w-md p-4 sm:pb-8 bg-white dark:bg-slate-700 sm:shadow-md overflow-hidden sm:rounded-lg"
            >
                <div class="mt-3 self-center">
                    <a href="#" @click.prevent="useRoute({ name: 'home'})">
                        <ApplicationLogo class="dark:fill-slate-200"/>
                    </a>
                </div>
                <h1 class="self-center font-sans text-4xl antialiased font-semibold leading-tight tracking-normal text-inherit dark:text-white">
                    MyPets</h1>
                <div class="self-center sm:text-lg mx-auto py-2 px-1">
                    The app to help take care of your pets
                </div>
                <slot/>
            </div>
        </div>
    </div>
</template>
