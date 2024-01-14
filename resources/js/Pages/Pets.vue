<script setup>
import {ref, watchEffect} from "vue";
import {Head} from "@inertiajs/vue3";
import PageSet from "@/Components/PageSet.vue";
import index from "@/Pages/Pets/Index.vue";
import create from "@/Pages/Pets/Create.vue";
import show from "@/Pages/Pets/Show.vue";
import edit from "@/Pages/Pets/Edit.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

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

const pageSet = ref(null);
watchEffect(() => {
    updateTitle();
});

const pageTitle = ref("Pets");
const updateTitle = () => {
    if (pageSet.value) {
        pageTitle.value = pageSet.value.pageTitle;
        switch (pageTitle.value) {
            case "Add":
                pageTitle.value += " Pet";
                break;
            case "Dashboard":
                pageTitle.value = "Pets";
                break;
            default:
        }
    }
};
</script>

<template>
    <Head :title="pageTitle"/>

    <AuthenticatedLayout @nav="(x) => pageSet.toRoute(x)">
        <template #pageTitle>{{ pageTitle }}</template>
        <PageSet
                ref="pageSet"
                :data="data"
                :initialId="route().params.pet"
                :initialRoute="route().current()"
                :meta="meta"
                :views="{index, create, show, edit}"
        />
    </AuthenticatedLayout>
</template>
