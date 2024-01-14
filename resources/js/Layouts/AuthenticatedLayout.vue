<script setup>
import {ref} from "vue";
import {router} from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLinkResponsive from "@/Components/NavLinkResponsive.vue";
import FAIcon from "@/Components/FAIcon.vue";

const emit = defineEmits(["nav"]);

const showingNavigationDropdown = ref(false);

function toDashboard() {
    if (route().current().slice(0, 4) === "pets") {
        emit("nav", "pets.index");
        showingNavigationDropdown.value = false;
    } else {
        router.visit(route("pets.index"));
    }
}
</script>

<template>

    <div class="min-h-screen bg-gray-100 sm:p-4">
        <div class="w-full max-w-7xl mx-auto">
            <nav class="max-w-7xl bg-white shadow-md sm:rounded-full">
                <!-- Primary Navigation Menu -->
                <div class="mx-2 sm:pr-2">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <a @click="$emit('nav','pets.index')">
                                    <ApplicationLogo
                                            class="block h-[55px] w-auto fill-current"
                                    />
                                </a>
                            </div>

                            <div class="m-2 mr-1">
                                <h1 class="font-semibold text-2xl text-gray-800 leading-tight">
                                    <slot name="pageTitle"/>
                                </h1>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex cursor-pointer">
                                <a v-if="!route().current('pets.index')" :active="route().current('pets.index')"
                                   class="font-medium text-lg" @click="toDashboard">
                                    All Pets
                                </a>
                            </div>
                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                    type="button"
                                                    class="inline-flex items-center py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                <FAIcon class="text-4xl" name="user"/>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <div class="p-2 border-b-2 font-bold text-center">{{ $page.props.auth.user.name
                                            }}
                                        </div>
                                        <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                        :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                        class="sm:hidden"
                >
                    <div class="">
                        <a v-if="!route().current('pets.index')" :active="route().current('pets.index')"
                           class="block w-full ps-4 pe-4 py-3 border-t-2 text-start text-xl font-bold text-gray-600 hover:text-gray-800 hover:bg-blue-100 hover:border-blue-100 focus:outline-none focus:text-gray-800 focus:bg-blue-200 focus:border-blue-200 transition duration-150 ease-in-out cursor-pointer"
                           @click="toDashboard">
                            All Pets
                        </a>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="border-t border-gray-200">
                        <div class="px-4 py-1 bg-gray-50">
                            <div class="font-medium text-base text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="">
                            <NavLinkResponsive :href="route('profile.edit')"> Profile</NavLinkResponsive>
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
              <slot/>
            </main>
        </div>
    </div>
</template>
