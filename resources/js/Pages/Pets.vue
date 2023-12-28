<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import {ref, toRaw, toRef} from "vue";
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
// const currentView = ref(currentRoute.value.split ('.').map (s => s.charAt (0).toUpperCase () + s.slice (1)).join (''));

function page(route_name, petId) {
    currentView.value = views[route_name];
    history.pushState(null, null, route(route_name, petId));
    pet.value = findPet(petId);
    pageTitle.value = petId ? pet.value.name : (route_name === 'pets.create' ? 'Add a Pet' : 'Pets')
}

function findPet(petId) {
    return toRaw(props.pets).filter(function (pet) {
        return pet._id === petId;
    })[0];
}

const pet = toRef(findPet(route().params.pet));
const pageTitle = ref(toRaw(pet.value) ? toRaw(pet.value).name : (currentRoute.value === 'pets.create' ? 'Add a Pet' : 'Pets'))

</script>

<template>
    <Head :title="pageTitle"/>

    <AuthenticatedLayout @nav="page">

        <template #header>
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">{{ pageTitle }}</h2>
        </template>

        <component :is="currentView" :pets="pets" :pet="pet" :message="message" @nav="page"/>

    </AuthenticatedLayout>

</template>
