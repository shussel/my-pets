<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import {ref} from "vue";
import PetsIndex from "@/Pages/Pets/Index.vue";
import PetsList from "@/Pages/Pets/List.vue";
import PetsCreate from "@/Pages/Pets/Create.vue";

const props = defineProps({
    data: {
        type: Object,
    },
    message: {
        type: String,
    },
});

const views = {
    'pets.index': PetsIndex,
    'pets.list': PetsList,
    'pets.create': PetsCreate,
}

const currentRoute = ref(route().current() || 'pets.index')
const currentView = ref(views[currentRoute.value])

function page(route) {
    currentView.value = views[route]
}

</script>

<template>
    <Head title="Pets" />

    <AuthenticatedLayout>

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pets</h2>
        </template>

        <component :is="currentView" @nav="(i) => page(i)" :data="data" :message="message"/>

    </AuthenticatedLayout>

</template>
