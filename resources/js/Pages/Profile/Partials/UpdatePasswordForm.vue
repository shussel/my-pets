<script setup>
import {ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import InputText from "@/Components/InputText.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import ButtonPrimary from "@/Components/ButtonPrimary.vue";

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
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
      <h2 class="ml-2 bg-white">Update Password</h2>

      <form class="border p-4 pt-5 -mt-3 space-y-3 lg:p-6" @submit.prevent="updatePassword">
          <div>
              <InputLabel for="current_password" value="Current Password"/>

              <InputText
                      id="current_password"
                      ref="currentPasswordInput"
                      v-model="form.current_password"
                      type="password"
                      class="mt-1 block w-full"
                      autocomplete="no"
              />

              <InputError :message="form.errors.current_password" class="mt-2"/>
          </div>

          <div class="flex gap-3 items-center">
              <div class="w-1/2">
                  <InputLabel for="password" value="New Password"/>
                  <InputText
                          id="password"
                          ref="passwordInput"
                          v-model="form.password"
                          type="password"
                          class="mt-1 block w-full"
                          autocomplete="new-password"
                  />
              </div>
              <div class="w-1/2">
                  <InputLabel for="password_confirmation" value="Confirm Password"/>
                  <InputText
                          id="password_confirmation"
                          v-model="form.password_confirmation"
                          type="password"
                          class="mt-1 block w-full"
                          autocomplete="new-password"
                  />
              </div>
          </div>
          <InputError :message="form.errors.password" class="mt-2"/>
          <InputError :message="form.errors.password_confirmation" class="mt-2"/>
          <div class="pt-1 flex gap-4 justify-between items-center">
              <div class="grow text-sm">Password must be at least 8 characters and include uppercase, lowercase, numbers
                  and symbols.
              </div>
              <div class="pr-2">
                  <ButtonPrimary v-if="!form.recentlySuccessful"
                                 :class="{ 'opacity-25': (form.processing || !form.current_password || !form.password || !form.password_confirmation) }"
                                 :disabled="form.processing || !form.current_password || !form.password || !form.password_confirmation">
                      Save
                  </ButtonPrimary>

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
    </form>
  </section>
</template>
