<script setup>
import {router} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Card from "@/Components/Card.vue";
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";

const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

function toHome() {
    router.get(route("pets.index"));
}
</script>

<template>

    <AuthenticatedLayout pageTitle="Profile" @nav="toHome">

        <card class="sm:max-w-md md:basis-[calc(50%-1em)]">
            <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
            />
        </card>

        <card class="sm:max-w-md md:basis-[calc(50%-1em)]">
            <UpdatePasswordForm/>
        </card>

        <card class="sm:max-w-md md:w-full md:max-w-[920px]">
            <DeleteUserForm/>
        </card>

    </AuthenticatedLayout>

</template>
