<script setup>
import {Head, useForm} from "@inertiajs/vue3";
import Layout from "@/Layouts/GuestLayout.vue";
import InputText from "@/Components/InputText.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import InputCheckbox from "@/Components/InputCheckbox.vue";
import ButtonPrimary from "@/Components/ButtonPrimary.vue";

defineOptions({layout: Layout});

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <Head title="Login"/>

    <h2 class="mt-3 -mb-3 z-10 self-center">Log In</h2>
    <div v-if="status" class="mb-4 font-medium text-sm text-green-600 text-center">
        {{ status }}
    </div>

    <form class="w-full max-w-sm mx-auto" @submit.prevent="submit">
        <div>
            <InputLabel for="email" value="Email"/>

            <InputText
                    id="email"
                    v-model="form.email"
                    autocomplete="username"
                    autofocus
                    class="mt-1 block w-full"
                    required
                    type="email"
            />

            <InputError :message="form.errors.email" class="mt-2"/>
        </div>

        <div class="mt-4">
            <InputLabel for="password" value="Password"/>

            <InputText
                    id="password"
                    v-model="form.password"
                    autocomplete="current-password"
                    class="mt-1 block w-full"
                    required
                    type="password"
            />

            <InputError :message="form.errors.password" class="mt-2"/>
        </div>

        <div class="block mt-4">
            <label class="flex items-center">
                <InputCheckbox v-model:checked="form.remember" name="remember"/>
                <span class="ms-2 text-sm text-slate-600 dark:text-slate-300">Remember me</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-4 gap-5">
            <a
                    class="underline text-sm text-slate-600 hover:text-slate-900
                        dark:text-slate-300 dark:hover:text-slate-200
                    cursor-pointer"
                    @click="$emit('nav',{ name:'register'})"
            >
                Register
            </a>
            <a
                    v-if="canResetPassword"
                    class="underline text-sm text-slate-600 hover:text-slate-900
                        dark:text-slate-300 dark:hover:text-slate-200
                    cursor-pointer"
                    @click="$emit('nav',{ name:'password.request'})"
            >
                Forgot password?
            </a>

            <ButtonPrimary :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="ms-4">
                Log in
            </ButtonPrimary>
        </div>
    </form>
</template>
