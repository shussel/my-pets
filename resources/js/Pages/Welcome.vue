<script setup>
import Layout from "@/Layouts/GuestLayout.vue";
import home from "./Auth/Home.vue";
import login from "./Auth/Login.vue";
import register from "./Auth/Register.vue";
import request from "./Auth/ForgotPassword.vue";
import useRoute from "@/Composables/useRoute.js";

defineOptions({ layout: Layout });

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

const { currentView } = useRoute(
    { name: route().current() },
    { home, login, register, request }
);

</script>

<template>
    <component :is="currentView" :canLogin="canLogin" :canRegister="canRegister" :canResetPassword="canResetPassword"
               :status="status" @nav="useRoute($event)"/>
</template>

