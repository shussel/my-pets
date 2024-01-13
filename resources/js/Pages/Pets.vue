<script setup>
import {ref, toRaw, computed} from "vue";
import {router} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Index from "@/Pages/Pets/Index.vue";
import Create from "@/Pages/Pets/Create.vue";
import Show from "@/Pages/Pets/Show.vue";
import Edit from "@/Pages/Pets/Edit.vue";

const props = defineProps({
    data: {
        type: Object,
    },
    meta: {
        type: Object,
    },
});

const currentRoute = ref(route().current() || "pets.index");

const views = {
    "pets.index": Index,
    "pets.create": Create,
    "pets.show": Show,
    "pets.edit": Edit,
};
const currentView = computed(() => {
    return views[currentRoute.value] || Index;
});

const currentId = ref(route().params.pet);
const currentData = computed(() => {
    return toRaw(props.data).filter(function(item) {
        return item._id === currentId.value;
    })[0];
});

const pageTitle = computed(() => {
    return currentData.value ? currentData.value.name : (currentRoute.value === "pets.create" ? "Add a Pet" : "Pets");
});

function toPage(route_name, id) {
    currentRoute.value = route_name;
    currentId.value = id;
    history.pushState(null, null, route(route_name, id));
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

        <component :is="currentView" :meta="meta" :pet="currentData" :pets="data" @nav="toPage"/>

    </AuthenticatedLayout>

</template>
