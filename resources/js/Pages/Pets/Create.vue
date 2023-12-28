<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import {Head, useForm} from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    /*form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });*/
};
</script>

<template>
    <div
        class="flex flex-col justify-start items-stretch w-full sm:max-w-md p-4 sm:p-8 bg-white shadow-md overflow-hidden sm:rounded-lg mx-auto"
    >
        <form @submit.prevent="submit" class="border px-4 py-2 rounded-lg">
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="flex gap-4">
                <div class="w-1/2 mt-4">
                    <InputLabel for="species" value="Species" />

                    <SelectInput
                        id="email"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.species"
                        required
                        autocomplete=""
                    />

                    <InputError class="mt-2" :message="form.errors.species" />
                </div>

                <div class="w-1/2 mt-4">
                    <InputLabel for="sex" value="Sex" />

                    <SelectInput
                        id="sex"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.sex"
                        required
                        autocomplete=""
                    />

                    <InputError class="mt-2" :message="form.errors.sex" />
                </div>
            </div>

            <div class="flex gap-4">
                <div class="w-1/4 mt-4">
                    <InputLabel for="age" value="Age" />

                    <TextInput
                        id="age"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.age"
                        required
                        autocomplete=""
                    />

                    <InputError class="mt-2" :message="form.errors.age" />
                </div>

                <div class="w-1/4 mt-4">
                    <InputLabel for="sex" value="Units" />

                    <SelectInput
                        id="sex"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.unit"
                        required
                        autocomplete=""
                    />

                    <InputError class="mt-2" :message="form.errors.unit" />
                </div>

                <div class="w-1/2 mt-4">
                    <InputLabel for="birth_date" value="Birth Date" />

                    <TextInput
                        id="birth_date"
                        type="date"
                        class="mt-1 block w-full"
                        v-model="form.birth_date"
                        required
                        autocomplete=""
                    />

                    <InputError class="mt-2" :message="form.errors.birth_date" />
                </div>
            </div>

            <div class="mt-4">
                <InputLabel for="image" value="Image" />

                <TextInput
                    id="image"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.image"
                    required
                    autocomplete=""
                />

                <InputError class="mt-2" :message="form.errors.image" />
            </div>

            <div class="flex items-center justify-end mt-4">

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Add Pet
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>



