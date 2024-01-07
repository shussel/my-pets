<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import {router} from '@inertiajs/vue3';

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

    <div
        class="flex flex-col justify-start items-stretch w-full sm:max-w-md px-4 sm:px-8 overflow-hidden mx-auto gap-4"
    >
      <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <UpdateProfileInformationForm
            :must-verify-email="mustVerifyEmail"
            :status="status"
            class="max-w-xl"
        />
      </div>

      <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <UpdatePasswordForm class="max-w-xl"/>
      </div>

      <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <DeleteUserForm class="max-w-xl"/>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
