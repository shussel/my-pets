<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import index from "@/Pages/Pets/Index.vue";
import create from "@/Pages/Pets/Create.vue";
import show from "@/Pages/Pets/Show.vue";
import edit from "@/Pages/Pets/Edit.vue";
import useRoute from "@/Composables/useRoute.js";
import useCurrentData from "@/Composables/useCurrentData.js";
import useCrudTitle from "@/Composables/useCrudTitle.js";

const props = defineProps({
    data: {
        type: Object,
        required: true
    },
    meta: {
        type: Object,
        default: {}
    },
});

const { currentView, baseRoute, currentRoute } = useRoute(
    { name: route().current(), params: route().params.pet },
    { index, create, show, edit }
);

const { currentData } = useCurrentData(currentRoute, props);

const { title: pageTitle } = useCrudTitle(baseRoute, currentData, "Pet", "Pets");

</script>

<template>
    <AuthenticatedLayout :pageTitle="pageTitle" @nav="useRoute($event)">

        <component :is="currentView" :currentData="currentData" :data="data" :meta="meta" @nav="useRoute($event)"/>

    </AuthenticatedLayout>
</template>
