<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
});

const updatePassword = () => {
  form.put(route('password.update'), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
    onError: () => {
      if (form.errors.password) {
        form.reset('password', 'password_confirmation');
        passwordInput.value.focus();
      }
      if (form.errors.current_password) {
        form.reset('current_password');
        currentPasswordInput.value.focus();
      }
    },
  });
};
</script>

<template>
  <section>
    <header>
      <h2 class="text-lg font-medium text-gray-900">Update Password</h2>
    </header>

    <form @submit.prevent="updatePassword" class="mt-3 space-y-3">
      <div>
        <InputLabel for="current_password" value="Current Password"/>

        <TextInput
            id="current_password"
            ref="currentPasswordInput"
            v-model="form.current_password"
            type="password"
            class="mt-1 block w-full"
            autocomplete="current-password"
        />

        <InputError :message="form.errors.current_password" class="mt-2"/>
      </div>

      <div>
        <InputLabel for="password" value="New Password"/>

        <div class="flex gap-3 items-center">
          <TextInput
              id="password"
              ref="passwordInput"
              v-model="form.password"
              type="password"
              class="mt-1 block w-1/2"
              autocomplete="new-password"
          />
          <div class="w=1/2">Password instructions</div>
        </div>

        <InputError :message="form.errors.password" class="mt-2"/>
      </div>

      <div>
        <InputLabel for="password_confirmation" value="Confirm Password"/>

        <div class="flex gap-3 items-center">
          <TextInput
              id="password_confirmation"
              v-model="form.password_confirmation"
              type="password"
              class="mt-1 block w-1/2"
              autocomplete="new-password"
          />
          <div class="w-1/2 text-right">
            <PrimaryButton v-if="!form.recentlySuccessful"
                           :disabled="form.processing || !form.current_password || !form.password || !form.password_confirmation"
                           :class="{ 'opacity-25': (form.processing || !form.current_password || !form.password || !form.password_confirmation) }">
              Save
            </PrimaryButton>

            <Transition
                enter-active-class="transition ease-in-out"
                enter-from-class="opacity-0"
                leave-active-class="transition ease-in-out"
                leave-to-class="opacity-0"
            >
              <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved</p>
            </Transition>
          </div>
        </div>

        <InputError :message="form.errors.password_confirmation" class="mt-2"/>
      </div>

      <div class="flex justify-end items-center mt-4">

      </div>
    </form>
  </section>
</template>
