<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import index from "@/Pages/Pets/Index.vue";
import create from "@/Pages/Pets/Create.vue";
import show from "@/Pages/Pets/Show.vue";
import edit from "@/Pages/Pets/Edit.vue";
import settings from "@/Pages/Pets/Settings.vue";
import schedule from "@/Pages/Pets/Schedule.vue";
import useRoute from "@/Composables/useRoute.js";
import useCurrentData from "@/Composables/useCurrentData.js";

defineOptions({ layout: AuthenticatedLayout });

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

const { pageRoute } = useRoute(
    { name: route().current(), params: route().params.pet },
    { index, create, show, edit, settings, schedule }
);

const { currentData } = useCurrentData(() => pageRoute.current, props);

</script>

<template>
    <component :is="pageRoute.view" :meta="meta" :pet="currentData" :pets="data"/>
</template>
