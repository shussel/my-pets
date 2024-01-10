<script setup>
import {nextTick, ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import ButtonDefault from "@/Components/ButtonDefault.vue";
import ButtonPrimary from "@/Components/ButtonPrimary.vue";
import InputText from "@/Components/InputText.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import Modal from "@/Components/Modal.vue";

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: "",
});

const enableDelete = ref(false);

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route("profile.destroy"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.reset();
};
</script>

<template>
    <section>
        <h2 class="ml-2 bg-white">Delete Account</h2>

        <div
                class="flex flex-col items-center gap-2 justify-center md:flex-row md:justify-between border p-4 pt-5 -mt-3 lg:p-6">
            <p class="mt-1 text-sm text-gray-600 md:w-1/2 md:gap-4">
                Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting
                your account, please download any data or information that you wish to retain.
            </p>
            <div class="mt-2 flex flex-col gap-4 md:w-1/2">
                <div class="text-center text-sm"><input v-model="enableDelete" type=checkbox> Yes, I want to delete my
                    account.
                </div>
                <div class="text-center">
                    <ButtonPrimary :class="{ 'opacity-25': !enableDelete }" :disabled="!enableDelete"
                                   @click="confirmUserDeletion">Delete Account
                    </ButtonPrimary>
                </div>
            </div>
        </div>

        <Modal :show="confirmingUserDeletion" maxWidth="sq" @close="closeModal">
            <div class="p-6">
                <h2 class="text-center">
                    Confirm Account Deletion
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Please enter your password to confirm you would like to permanently delete your account.
                </p>

                <div class="mt-8">
                    <InputLabel class="sr-only" for="password" value="Password"/>

                    <InputText
                            id="password"
                            ref="passwordInput"
                            v-model="form.password"
                            autocomplete="no"
                            class="mt-1 block w-3/4 mx-auto"
                            placeholder="Password"
                            type="password"
                            @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2"/>
                </div>

                <div class="mt-6 flex justify-center gap-4">
                    <ButtonDefault @click="closeModal">Cancel</ButtonDefault>

                    <ButtonPrimary
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                            class=""
                            @click="deleteUser"
                    >Delete
                    </ButtonPrimary>
                </div>
            </div>
        </Modal>
    </section>
</template>
