<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import {ref} from "vue";
import PetsIndex from "@/Pages/Pets/Index.vue";
import PetsList from "@/Pages/Pets/List.vue";
import PetsCreate from "@/Pages/Pets/Create.vue";
import PetsShow from "@/Pages/Pets/Show.vue";

const props = defineProps({
    pets: {
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
    'pets.show': PetsShow,
}

const currentRoute = ref(route().current() || 'pets.index')
const currentView = ref(views[currentRoute.value])

function page(route, param) {

    currentView.value = views[route]
}

const petId = ref(route().params.pet)

</script>

<template>
    <Head title="Pets"/>

    <AuthenticatedLayout>

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pets</h2>
        </template>

        <component :is="currentView" :pets="pets" :petId="petId" :message="message" @pet="petId=$event"
                   @nav="page($event)"/>

    </AuthenticatedLayout>

</template>
