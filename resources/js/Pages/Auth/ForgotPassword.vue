<script setup>
import {Head, useForm} from "@inertiajs/vue3";
import Layout from "@/Layouts/GuestLayout.vue";
import InputText from "@/Components/InputText.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import ButtonPrimary from "@/Components/ButtonPrimary.vue";

defineOptions({layout: Layout});

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <Head title="Forgot Password"/>

    <h2 class="mt-3 mb-2 z-10 self-center">Forgot Password</h2>
    <div class="mb-4 text-sm text-slate-600 dark:text-slate-300 text-center">
        Enter your registered email address for a password reset link.
    </div>

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

        <div class="flex items-center justify-center mt-4">
            <ButtonPrimary :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Email Password Reset Link
            </ButtonPrimary>
        </div>
    </form>
</template>
