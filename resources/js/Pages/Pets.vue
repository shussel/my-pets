<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {ref, toRaw, toRef} from "vue";
import PetsIndex from "@/Pages/Pets/Index.vue";
import PetsCreate from "@/Pages/Pets/Create.vue";
import PetsShow from "@/Pages/Pets/Show.vue";
import PetsEdit from "@/Pages/Pets/Edit.vue";

const props = defineProps({
    pets: {
        type: Object,
    },
    meta: {
        type: Object,
    },
    message: {
        type: String,
    },
});

const currentRoute = ref(route().current() || 'pets.index')

const views = {
    'pets.index': PetsIndex,
    'pets.create': PetsCreate,
    'pets.show': PetsShow,
    'pets.edit': PetsEdit,
}
const currentView = ref(views[currentRoute.value] || PetsIndex)

function findPet(petId) {
    return toRaw(props.pets).filter(function (pet) {
        return pet._id === petId;
    })[0];
}

const pet = toRef(findPet(route().params.pet));
const species = toRef(toRaw(pet.value) ? toRaw(pet.value).species : '');

function getTitle(route, pet) {
    return pet ? pet.name : (route === 'pets.create' ? 'Add a Pet' : 'Pets');
}

const pageTitle = ref(getTitle(currentRoute.value, toRaw(pet.value)));

function toPage(route_name, petId) {
    currentView.value = views[route_name];
    history.pushState(null, null, route(route_name, petId));
    pet.value = findPet(petId);
    pageTitle.value = getTitle(route_name, pet.value)
    species.value = pet.value ? pet.value.species : ''
}
</script>

<template>

    <AuthenticatedLayout :pageTitle="pageTitle" :species="species" @nav="toPage">

        <div v-if="message">{{ message }}</div>

        <component :is="currentView" :pets="pets" :pet="pet" :meta="meta" :message="message" @nav="toPage"/>

    </AuthenticatedLayout>

</template>
