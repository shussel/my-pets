<script setup>
import {computed} from "vue";
import {Head, Link, useForm} from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import ButtonPrimary from "@/Components/ButtonPrimary.vue";

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route("verification.send"));
};

const verificationLinkSent = computed(() => props.status === "verification-link-sent");
</script>

<template>
    <GuestLayout>
        <Head title="Email Verification"/>

        <div class="p-2 w-full max-w-sm mx-auto">

            <div class="mb-4 text-sm text-slate-600">
                Thanks for signing up! Before getting started, please check your email and click the verification
                button
                we
                just sent. If you didn't receive the email, you can resend it below.
            </div>

            <div v-if="verificationLinkSent" class="mb-4 font-medium text-sm text-green-600">
                A new verification link has been sent to the email address you provided during registration.
            </div>

            <form @submit.prevent="submit">
                <div class="mt-4 flex items-center justify-between">
                    <ButtonPrimary :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Resend Verification Email
                    </ButtonPrimary>

                    <Link
                            :href="route('logout')"
                            as="button"
                            class="underline text-sm text-slate-600 hover:text-slate-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lt/50"
                            method="post"
                    >Log Out
                    </Link
                    >
                </div>
            </form>
        </div>
    </GuestLayout>
</template>
