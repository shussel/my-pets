<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import LoginForm from './Auth/Partials/LoginForm.vue';
import RegisterForm from './Auth/Partials/RegisterForm.vue';
import ForgotPasswordForm from './Auth/Partials/ForgotPasswordForm.vue';
import ResetPasswordForm from './Auth/Partials/ResetPasswordForm.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

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
    route: {
        type: String,
        default: 'login'
    },
});

const form = ref(props.route || 'login')

function loginMode() {
    form.value = 'login'
}
function registerMode() {
    form.value = 'register'
}
function forgotMode() {
    form.value = 'forgot'
}
</script>

<template>
    <GuestLayout>
        <Head title="Welcome" />
        <div class="text-lg mx-auto p-6 lg:p-8">
            The app to help take care of your pets
        </div>
        <div  v-if="canLogin || canRegister" class="flex gap-2 mb-3">
            <PrimaryButton v-if="canLogin" class="w-full" @click="loginMode" :disabled="form === 'login'">Log In</PrimaryButton>
            <PrimaryButton v-if="canRegister" @click="registerMode" :disabled="form === 'register'">Register</PrimaryButton>
        </div>
        <div class="self-stretch" v-if="canLogin && form === 'login'">
            <LoginForm :status="status" :canResetPassword="canResetPassword"/>
        </div>
        <div class="self-stretch" v-if="canRegister && form === 'register'">
            <RegisterForm :status="status" />
        </div>
        <div class="self-stretch" v-if="canResetPassword && form === 'forgot'">
            <ForgotPasswordForm :status="status" />
        </div>
        <div class="self-stretch" v-if="canResetPassword && form === 'reset'">
            <ResetPasswordForm :status="status" />
        </div>
    </GuestLayout>
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
