<script setup>
import {computed} from "vue";
import {Head, useForm} from "@inertiajs/vue3";
import Layout from "@/Layouts/GuestLayout.vue";
import InputText from "@/Components/InputText.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import ButtonPrimary from "@/Components/ButtonPrimary.vue";

defineOptions({layout: Layout});

const form = useForm({
    name: "",
    email: "",
    zip_code: "",
    password: "",
    password_confirmation: "",
});

const disableButton = computed(() => {
    return form.processing || !form.name || !form.email || !form.password || !form.password_confirmation;
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <Head title="Register"/>

    <h2 class="mt-3 -mb-3 z-10 self-center">Register</h2>
    <form class="w-full max-w-sm mx-auto" @submit.prevent="submit">
        <div>
            <InputLabel for="name" value="Name"/>

            <InputText
                    id="name"
                    v-model="form.name"
                    autocomplete="name"
                    autofocus
                    class="mt-1 block w-full"
                    required
                    type="text"
            />

            <InputError :message="form.errors.name" class="mt-2"/>
        </div>

        <div class="mt-4">
            <InputLabel for="email" value="Email"/>

            <InputText
                    id="email"
                    v-model="form.email"
                    autocomplete="username"
                    class="mt-1 block w-full"
                    required
                    type="email"
            />

            <InputError :message="form.errors.email" class="mt-2"/>
        </div>

        <div class="mt-4 flex gap-3 items-end">
            <div class="w-1/2">
                <InputLabel for="zip_code" value="Zip Code"/>

                <InputText
                        id="zip_code"
                        v-model="form.zip_code"
                        autocomplete="postal-code"
                        class="mt-1 block w-full"
                        type="text"
                />

                <InputError :message="form.errors.zip_code" class="mt-2"/>
            </div>
            <div class="w-1/2 text-sm text-slate-500 dark:text-slate-300 italic">to help connect with local pet
                resources
            </div>
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

        <div class="mt-4 text-center text-sm text-slate-500 dark:text-slate-300 italic">Password must be at least 8
            characters and include
            uppercase,
            lowercase, numbers and
            symbols.
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
            <a
                    class="underline text-sm text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-slate-100 cursor-pointer"
                    @click="$emit('nav','login')"
            >
                Already registered?
            </a>

            <ButtonPrimary :class="{ 'opacity-25': disableButton }" :disabled="disableButton" class="ms-4">
                Register
            </ButtonPrimary>
        </div>
    </form>
</template>
