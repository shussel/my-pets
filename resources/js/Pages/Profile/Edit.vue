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

    <div class="p-4 sm:p-8 bg-white shadow w-full max-w-md rounded-lg sm:basis-[calc(50%-.75em)]">
      <UpdateProfileInformationForm
          :must-verify-email="mustVerifyEmail"
          :status="status"
      />
    </div>

    <div class="p-4 sm:p-8 bg-white shadow w-full max-w-md rounded-lg sm:basis-[calc(50%-.75em)]">
      <UpdatePasswordForm/>
    </div>

    <div class="p-4 sm:p-8 bg-white shadow w-full max-w-md rounded-lg sm:max-w-[920px]">
      <DeleteUserForm/>
    </div>

  </AuthenticatedLayout>

</template>
