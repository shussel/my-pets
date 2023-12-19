<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import Home from './Auth/Home.vue';
import Login from './Auth/Login.vue';
import Register from './Auth/Register.vue';
import ForgotPassword from './Auth/ForgotPassword.vue';
import ResetPassword from './Auth/ResetPassword.vue';
import Layout from '@/Layouts/GuestLayout.vue';

defineOptions({ layout: Layout })

const views = {
    'home': Home,
    'login': Login,
    'register': Register,
    'password.request': ForgotPassword,
    'password.reset': ResetPassword,
}

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
    },
    email: {
        type: String,
    },
    token: {
        type: String,
    }
});

const currentRoute = ref(route().current() || '')
const currentView = ref(views[currentRoute.value])

function page(route) {
    currentView.value = views[route]
    console.log(route)
}

</script>

<template>
        <component :is="currentView" @nav="(i) => page(i)" :canLogin="canLogin" :canRegister="canRegister" :canResetPassword="canResetPassword" :status="status" :email="email" :token="token" />
<!--        <Head title="Welcome" />-->
<!--        <div  v-if="(form === 'home') && (canLogin || canRegister)" class="w-1/2 mt-5 mb-6">-->
<!--            <h2 class="text-center p-2">Existing Users</h2>-->
<!--            <BoneButton v-if="canLogin" @click="page('login')" class="mb-4">Log In</BoneButton>-->
<!--            <h2 class="text-center p-2">New Users</h2>-->
<!--            <BoneButton v-if="canRegister" @click="page('register')">Register</BoneButton>-->
<!--        </div>-->
<!--        <div class="self-stretch" v-if="canLogin && form === 'login'">-->
<!--            <LoginForm @forgot="page('forgot')" :status="status" :canResetPassword="canResetPassword"/>-->
<!--        </div>-->
<!--        <div class="self-stretch" v-if="canRegister && form === 'register'">-->
<!--            <RegisterForm @login="page('login')" />-->
<!--        </div>-->
<!--        <div class="self-stretch" v-if="canResetPassword && form === 'forgot'">-->
<!--            <ForgotPasswordForm :status="status" />-->
<!--        </div>-->
<!--        <div class="self-stretch" v-if="canResetPassword && form === 'reset'">-->
<!--            <ResetPasswordForm :email="email" :token="token"/>-->
<!--        </div>-->
</template>

