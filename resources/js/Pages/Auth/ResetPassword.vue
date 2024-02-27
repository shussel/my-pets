<script setup>
import {Head, useForm} from "@inertiajs/vue3";
import Layout from "@/Layouts/GuestLayout.vue";
import InputText from "@/Components/InputText.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import ButtonPrimary from "@/Components/ButtonPrimary.vue";

defineOptions({layout: Layout});

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("password.store"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <Head title="Reset Password"/>

    <h2 class="mt-3 -mb-3 z-10 self-center">Change Password</h2>

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
                    autocomplete="new-password"
                    class="mt-1 block w-full"
                    required
                    type="password"
            />

            <InputError :message="form.errors.password" class="mt-2"/>
        </div>

        <div class="mt-4">
            <InputLabel for="password_confirmation" value="Confirm Password"/>

            <InputText
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    autocomplete="new-password"
                    class="mt-1 block w-full"
                    required
                    type="password"
            />

            <InputError :message="form.errors.password_confirmation" class="mt-2"/>
        </div>

        <div class="flex items-center justify-end mt-4">
            <ButtonPrimary :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Reset Password
            </ButtonPrimary>
        </div>
    </form>
</template>
