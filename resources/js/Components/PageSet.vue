<script setup>
import {ref, toValue, toRaw, computed, watchEffect} from "vue";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    views: {
        type: Object,
        required: true
    },
    data: {
        type: Object,
        required: true
    },
    meta: {
        type: Object,
        default: {}
    },
    initialRoute: {
        type: String,
        default: "index"
    },
    initialId: {
        type: String,
        default: ""
    },
});

const currentRoute = ref(props.initialRoute);
const baseRoute = ref(null);
watchEffect(() => {
    baseRoute.value = currentRoute.value.substring(currentRoute.value.indexOf(".") + 1);
});
const currentView = computed(() => {
    return props.views[baseRoute.value];
});

const currentId = ref(props.initialId);

function toRoute(route_name, id) {
    currentRoute.value = route_name;
    currentId.value = id;
    history.pushState(null, null, route(toValue(route_name), toValue(id)));
    setTimeout(() => {
        showMessage.value = false;
    }, 5000);
}

const showMessage = ref(true);
router.on("finish", () => {
    showMessage.value = true;
});

const currentData = computed(() => {
    return toRaw(props.data).filter(function(item) {
        return item._id === toValue(currentId);
    })[0];
});

const pageTitle = computed(() => {
    switch (baseRoute.value) {
        case "create":
            return "Add";
        case "show":
            return (currentId.value && currentData.value ? " " + currentData.value.name : "");
        case "edit":
            return "Edit" + (currentId.value && currentData.value ? " " + currentData.value.name : "");
        case "index":
        default:
            return "Dashboard";
    }
});

defineExpose({
    pageTitle,
    toRoute
});
</script>

<template>

    <div v-if="showMessage && $page.props.flash.message" class="text-center font-bold w-full">
        {{ $page.props.flash.message }}
    </div>

    <component :is="currentView" :meta="meta" :pet="currentData" :pets="data" @nav="toRoute"/>

</template>
