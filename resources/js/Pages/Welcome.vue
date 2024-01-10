<script setup>
import {ref} from "vue";
import Home from "./Auth/Home.vue";
import Login from "./Auth/Login.vue";
import Register from "./Auth/Register.vue";
import ForgotPassword from "./Auth/ForgotPassword.vue";
import Layout from "@/Layouts/GuestLayout.vue";

defineOptions({layout: Layout});

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    }
});

const currentRoute = ref(route().current() || "home");

const views = {
    "home": Home,
    "login": Login,
    "register": Register,
    "password.request": ForgotPassword,
};

const currentView = ref(views[currentRoute.value]);

function toPage(route_name) {
    currentView.value = views[route_name];
    history.pushState(null, null, route(route_name));
}

</script>

<template>
    <component :is="currentView" :canLogin="canLogin" :canRegister="canRegister" :canResetPassword="canResetPassword"
               :status="status" @nav="(i) => toPage(i)"/>
</template>

