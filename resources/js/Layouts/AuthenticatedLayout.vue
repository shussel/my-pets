<script setup>
import { ref } from "vue";
import { Head, router } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLinkResponsive from "@/Components/NavLinkResponsive.vue";
import FAIcon from "@/Components/FAIcon.vue";
import Modal from "@/Components/Modal.vue";
import SettingsForm from "@/Pages/Settings/SettingsForm.vue";
import useSettings from "@/Composables/useSettings.js";
import useRoute from "@/Composables/useRoute.js";
import useFlashMessage from "@/Composables/useFlashMessage.js";

const props = defineProps({
    pageTitle: {
        type: String,
        default: "MyPets"
    },
});

const { settings, useDark } = useSettings();

// settings modal
const showSettings = ref(false);
const openSettings = () => {
    showSettings.value = true;
    showingNavigationDropdown.value = false;
};

const showingNavigationDropdown = ref(false);

function toDashboard() {
    if (route().current().slice(0, 4) === "pets") {
        useRoute({ name: "pets.index" });
        showingNavigationDropdown.value = false;
    } else {
        router.visit(route("pets.index"));
    }
}

const { message } = useFlashMessage();

</script>

<template>

    <Head :title="pageTitle"/>

    <div :class="{ dark: useDark }" :data-theme="settings.theme || 'default'">

        <div class="min-h-screen bg-slate-100 dark:bg-slate-900 dark:text-slate-200 sm:p-4">
            <div class="w-full max-w-7xl mx-auto">
                <nav class="max-w-7xl bg-white dark:bg-slate-700 shadow-md sm:rounded-full">
                    <!-- Primary Navigation Menu -->
                    <div class="mx-2 sm:pr-2">
                        <div class="flex justify-between h-16">
                            <div class="flex items-center">
                                <!-- Logo -->
                                <div class="shrink-0 flex items-center">
                                    <a @click.prevent="toDashboard">
                                        <ApplicationLogo
                                                class="block h-[55px] w-auto fill-black dark:fill-slate-200"
                                        />
                                    </a>
                                </div>

                                <div class="m-2 mr-1">
                                    <h1 class="font-semibold text-2xl leading-tight dark:text-slate-100">
                                        {{ pageTitle }}
                                    </h1>
                                </div>
                            </div>

                            <div class="hidden sm:flex sm:items-center sm:ms-6">
                                <!-- Navigation Links -->
                                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex cursor-pointer">
                                    <a v-if="!route().current('pets.index')"
                                       class="font-medium text-lg" @click.prevent="toDashboard">
                                        Pets
                                    </a>
                                </div>
                                <!-- Settings Dropdown -->
                                <div class="ms-3 relative">
                                    <Dropdown align="right" width="48">
                                        <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                    type="button"
                                                    class="inline-flex items-center py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-700 hover:text-slate-700 dark:hover:text-slate-300 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                <FAIcon class="text-4xl" name="user"/>
                                            </button>
                                        </span>
                                        </template>

                                        <template #content>
                                            <div class="p-2 border-b-2 dark:border-slate-600 font-bold text-center dark:text-slate-300">
                                                {{ $page.props.auth.user.name
                                                }}
                                            </div>
                                            <a class="block w-full p-3 text-start leading-5 text-slate-800 dark:text-slate-300 hover:bg-lt/30 focus:outline-none focus:bg-lt/30 dark:focus:bg-lt/30 transition duration-150 ease-in-out"
                                               href="#" @click.prevent="openSettings"> Settings</a>
                                            <DropdownLink :href="route('profile.edit')"> Profile</DropdownLink>
                                            <DropdownLink :href="route('logout')" as="button" method="post">
                                                Log Out
                                            </DropdownLink>
                                        </template>
                                    </Dropdown>
                                </div>
                            </div>

                            <!-- Hamburger -->
                            <div class="-me-2 flex items-center sm:hidden">
                                <button
                                        class="mr-2 inline-flex items-center justify-center p-2 rounded-md text-slate-400 dark:bg-slate-700 dark:text-slate-400 hover:text-slate-300 dark:hover:bg-slate-500 hover:bg-slate-100 focus:outline-none focus:bg-slate-100 focus:text-slate-500 transition duration-150 ease-in-out"
                                        @click="showingNavigationDropdown = !showingNavigationDropdown"
                                >
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                                :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                                d="M4 6h16M4 12h16M4 18h16"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                        />
                                        <path
                                                :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                                d="M6 18L18 6M6 6l12 12"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Responsive Navigation Menu -->
                    <div
                            :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                            class="sm:hidden bg-white dark:bg-slate-700"
                    >
                        <a v-if="!route().current('pets.index')" :active="route().current('pets.index')"
                           class="block w-full mt-1 ps-4 pe-4 py-3 text-start text-2xl font-bold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-slate-100 hover:bg-lt/50 dark:hover:bg-lt/50 focus:text-slate-900 dark:focus:text-slate-100 focus:bg-lt/50 dark:focus:bg-lt/50  transition duration-150 ease-in-out cursor-pointer border-t border-t-slate-200 dark:border-t-slate-500"
                           @click.prevent="toDashboard">
                            Pets
                        </a>

                        <!-- Responsive Settings Options -->
                        <div>
                            <div class="px-4 py-2 flex justify-center items-center gap-2 bg-slate-100 dark:bg-slate-800">
                                <div>
                                    <FAIcon class="text-4xl" name="user"/>
                                </div>
                                <div class="grow">
                                    <div class="font-medium text-base text-slate-800 dark:text-slate-200">
                                        {{ $page.props.auth.user.name }}
                                    </div>
                                    <div class="font-medium text-sm text-slate-500 dark:text-slate-300">
                                        {{ $page.props.auth.user.email }}
                                    </div>
                                </div>
                            </div>

                            <div class="">
                                <a class="block w-full ps-8 pe-4 py-2 text-start text-lg font-bold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-slate-100 hover:bg-lt/50 dark:hover:bg-lt/50 focus:text-slate-900 dark:focus:text-slate-100 focus:bg-lt/50 transition duration-150 ease-in-out border-b border-b-slate-200 dark:border-b-slate-500"
                                   href="#" @click.prevent="openSettings"> Settings</a>
                                <NavLinkResponsive :active="route().current('profile.edit')"
                                                   :href="route('profile.edit')"> Profile
                                </NavLinkResponsive>
                                <NavLinkResponsive :href="route('logout')" as="button" method="post">
                                    Log Out
                                </NavLinkResponsive>
                            </div>
                        </div>
                    </div>
                </nav>
                <!-- Page Content -->
                <main
                        class="m-3 flex flex-col justify-start items-center gap-4 sm:m-6 sm:flex-row sm:justify-center sm:items-stretch sm:flex-wrap sm:gap-6">
                    <div v-if="message" class="text-center font-bold w-full">
                        {{ message }}
                    </div>
                    <slot/>
                </main>
            </div>
        </div>
    </div>

    <Modal :paw="false" :show="showSettings" maxWidth="sm">
        <SettingsForm @closeSettings="showSettings = false"/>
    </Modal>
</template>
