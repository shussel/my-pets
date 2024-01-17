<script setup>
import {Link, useForm, usePage} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import ButtonPrimary from "@/Components/ButtonPrimary.vue";
import InputText from "@/Components/InputText.vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
    zip_code: user.zip_code,
});
</script>

<template>
    <section>
        <h2 class="ml-2">Profile Info</h2>

        <form class="border p-4 pt-5 -mt-3 space-y-3 lg:p-6" @submit.prevent="form.patch(route('profile.update'))">
            <div>
                <InputLabel for="name" value="Name"/>

                <InputText
                        id="name"
                        v-model="form.name"
                        autocomplete="name"
                        class="mt-1 block w-full"
                        required
                        type="text"
                />

                <InputError :message="form.errors.name" class="mt-2"/>
            </div>

            <div>
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

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-slate-800">
                    Your email address is unverified.
                    <Link
                            :href="route('verification.send')"
                            as="button"
                            class="underline text-sm text-slate-600 hover:text-slate-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lt/50"
                            method="post"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                        v-show="status === 'verification-link-sent'"
                        class="mt-2 font-medium text-sm text-green-600"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div>
                <InputLabel for="zip_code" value="Zip Code"/>

                <div class="flex gap-3 items-center">

                    <InputText
                            id="zip_code"
                            v-model="form.zip_code"
                            autocomplete="postal-code"
                            class="mt-1 block w-1/2"
                            type="text"
                    />

                    <div class="w-1/2 text-right pr-2">
                        <ButtonPrimary v-if="!form.recentlySuccessful"
                                       :class="{ 'opacity-25': (form.processing || !form.isDirty) }"
                                       :disabled="form.processing || !form.isDirty">Save
                        </ButtonPrimary>

                        <Transition
                                enter-active-class="transition ease-in-out"
                                enter-from-class="opacity-0"
                                leave-active-class="transition ease-in-out"
                                leave-to-class="opacity-0"
                        >
                            <p v-if="form.recentlySuccessful" class="text-sm text-slate-600">Saved</p>
                        </Transition>
                    </div>

                </div>

                <InputError :message="form.errors.zip_code" class="mt-2"/>
            </div>

        </form>
    </section>
</template>
