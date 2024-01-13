<script setup>
import {ref, computed} from "vue";
import {router} from "@inertiajs/vue3";
import {useRouteView} from "@/Components/RouteView";
import {useCurrentData} from "@/Components/CurrentData";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import index from "@/Pages/Pets/Index.vue";
import create from "@/Pages/Pets/Create.vue";
import show from "@/Pages/Pets/Show.vue";
import edit from "@/Pages/Pets/Edit.vue";

const props = defineProps({
    data: {
        type: Object,
    },
    meta: {
        type: Object,
    },
});

const currentRoute = ref(route().current());
const currentId = ref(route().params.pet);

const {view} = useRouteView(currentRoute, currentId, {index, create, show, edit});
const {currentData} = useCurrentData(currentId, props.data);

const pageTitle = computed(() => {
    return currentData.value ? currentData.value.name : (currentRoute.value === "pets.create" ? "Add a Pet" : "Pets");
});

function toPage(route_name, id) {
    currentRoute.value = route_name;
    currentId.value = id;
    setTimeout(() => {
        showMessage.value = false;
    }, 5000);
}

const showMessage = ref(true);
router.on("finish", (event) => {
    showMessage.value = true;
});

</script>

<template>

    <AuthenticatedLayout :icon="currentData ? currentData.species : null" :pageTitle="pageTitle" @nav="toPage">

        <div v-if="showMessage && $page.props.flash.message" class="text-center font-bold w-full">
            {{ $page.props.flash.message }}
        </div>

        <component :is="view" :meta="meta" :pet="currentData" :pets="data" @nav="toPage"/>

    </AuthenticatedLayout>

</template>
