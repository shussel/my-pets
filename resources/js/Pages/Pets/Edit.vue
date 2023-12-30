<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import FAIcon from '@/Components/FAIcon.vue';
import {useForm} from '@inertiajs/vue3';
import {defineEmits} from "vue";

const props = defineProps({
    meta: {
        type: Object,
    },
    message: {
        type: String,
    },
    pet: {
        type: Object,
        required: true,
    }
});

const form = useForm({
    _id: props.pet._id,
    name: props.pet.name,
    species: props.pet.species,
    sex: props.pet.sex,
    weight: props.pet.weight,
    birth_date: props.pet.birth_date,
    image: props.pet.image,
});

const emit = defineEmits(['nav']);

const submit = (petId) => {
    form.patch(route('pets.update', petId), {
        onSuccess: () => {
            emit('nav', 'pets.index')
        },
    });
};

</script>

<template>
    <div
        class="flex flex-col justify-start items-stretch w-full sm:max-w-md p-4 sm:p-8 bg-white shadow-md overflow-hidden sm:rounded-lg mx-auto"
    >
        <form @submit.prevent="submit(pet._id)" class="border px-4 py-2 rounded-lg">

            <div class="text-center">
                <img v-if="form.image" class="w-[200px] h-[200px] rounded-full mx-auto"
                     :src="form.image + '?p=' + form._id" :alt="form.name"/>
                <FAIcon v-else-if="form.species" :name="form.species"
                        class="bg-blue-100 text-white w-[152px] h-[152px] rounded-full p-6"/>
            </div>

            <div>
                <InputLabel for="name" value="Name"/>

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name"/>
            </div>

            <!--            <div class="flex gap-2 justify-center items-center flex-wrap py-4">-->
            <!--                <SecondaryButton v-for="specie in meta.species" class="w-auto text-xl"><SpeciesIcon class="mx-1" :species="specie.value" />{{ specie.label }}</SecondaryButton>-->
            <!--            </div>-->

            <!--            <div class="flex gap-2 justify-center items-center flex-wrap pb-4">-->
            <!--                <SecondaryButton v-for="sex in meta.sexes" class="w-auto text-xl">{{ sex.label }}</SecondaryButton>-->
            <!--            </div>-->

            <div class="flex gap-4">
                <div class="w-1/2 mt-4">
                    <InputLabel for="species" value="Species"/>

                    <SelectInput
                        :options="meta.species"
                        id="species"
                        class="mt-1 block w-full"
                        v-model="form.species"
                        required
                        autocomplete=""
                    />

                    <InputError class="mt-2" :message="form.errors.species"/>
                </div>

                <div class="w-1/2 mt-4">
                    <InputLabel for="sex" value="Sex"/>

                    <SelectInput
                        :options="meta.sexes"
                        id="sex"
                        class="mt-1 block w-full"
                        v-model="form.sex"
                        required
                        autocomplete=""
                    />

                    <InputError class="mt-2" :message="form.errors.sex"/>
                </div>
            </div>

            <div class="flex gap-4">
                <!--                <div class="w-1/4 mt-4">-->
                <!--                    <InputLabel for="age" value="Age" />-->

                <!--                    <TextInput-->
                <!--                        id="age"-->
                <!--                        type="text"-->
                <!--                        class="mt-1 block w-full"-->
                <!--                        v-model="form.age"-->
                <!--                        required-->
                <!--                        autocomplete=""-->
                <!--                    />-->

                <!--                    <InputError class="mt-2" :message="form.errors.age" />-->
                <!--                </div>-->

                <!--                <div class="w-1/4 mt-4">-->
                <!--                    <InputLabel for="sex" value="Units" />-->

                <!--                    <SelectInput-->
                <!--                        :options="meta.units"-->
                <!--                        id="sex"-->
                <!--                        class="mt-1 block w-full"-->
                <!--                        v-model="form.unit"-->
                <!--                        required-->
                <!--                        autocomplete=""-->
                <!--                    />-->

                <!--                    <InputError class="mt-2" :message="form.errors.unit" />-->
                <!--                </div>-->

                <div class="w-1/2 mt-4">
                    <InputLabel for="weight" value="Weight"/>

                    <TextInput
                        id="weight"
                        type="number"
                        class="mt-1 block w-full"
                        v-model="form.weight"
                        autocomplete=""
                    />

                    <InputError class="mt-2" :message="form.errors.weight"/>
                </div>

                <div class="w-1/2 mt-4">
                    <InputLabel for="birth_date" value="Birth Date"/>

                    <TextInput
                        id="birth_date"
                        type="date"
                        class="mt-1 block w-full"
                        v-model="form.birth_date"
                        required
                        autocomplete=""
                    />

                    <InputError class="mt-2" :message="form.errors.birth_date"/>
                </div>
            </div>

            <!--            <div class="mt-4">-->
            <!--                <InputLabel for="image" value="Image"/>-->

            <!--                <TextInput-->
            <!--                    id="image"-->
            <!--                    type="text"-->
            <!--                    class="mt-1 block w-full"-->
            <!--                    v-model="form.image"-->
            <!--                    autocomplete=""-->
            <!--                />-->

            <!--                <InputError class="mt-2" :message="form.errors.image"/>-->
            <!--            </div>-->

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Update Pet
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>



