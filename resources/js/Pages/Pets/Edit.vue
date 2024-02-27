<script setup>
import { computed, ref, toRaw } from "vue";
import { useForm } from "@inertiajs/vue3";
import InputText from "@/Components/InputText.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import InputAvatar from "@/Components/InputAvatar.vue";
import InputButtons from "@/Components/InputButtons.vue";
import BirthdayCalc from "@/Components/BirthdayCalc.vue";
import ButtonDefault from "@/Components/ButtonDefault.vue";
import ButtonPrimary from "@/Components/ButtonPrimary.vue";
import ModalConfirm from "@/Components/ModalConfirm.vue";
import Card from "@/Components/Card.vue";
import usePageTitle from "@/Composables/usePageTitle.js";
import usePetAI from "@/Composables/usePetAI.js";
import useRoute from "@/Composables/useRoute.js";

const props = defineProps({
    meta: {
        type: Object,
    },
    pet: {
        type: Object,
        required: true,
    }
});

usePageTitle("Edit " + props.pet.name);

const form = useForm({
    _id: props.pet._id,
    name: props.pet.name,
    species: props.pet.species,
    sex: props.pet.sex,
    weight: props.pet.weight,
    birth_date: props.pet.birth_date,
    avatar: props.pet.avatar,
    newAvatar: null,
    _method: "put"
});

const startData = { ...props.pet };

const maxAge = computed(() => {
    if (!form.species) {
        return 20;
    }
    return toRaw(props.meta.species).filter(function(specie) {
        return specie.value === form.species;
    })[0]["maxAge"];
});

const maxBirthday = computed(() => {
    return new Date().toISOString().substr(0, 10);
});

const minBirthday = computed(() => {
    return new Date(new Date().setFullYear(new Date().getFullYear() - maxAge.value)).toISOString().substr(0, 10);
});

const maxWeight = computed(() => {
    if (!form.species) {
        return 100;
    }
    return toRaw(props.meta.species).filter(function(specie) {
        return specie.value === form.species;
    })[0]["maxWeight"];
});

const inputAvatar = ref(null);
const newAvatar = ref(false);
const submit = () => {
    inputAvatar.value.crop();
};
const saveWithCrop = (cropped) => {
    if (cropped) {
        form.newAvatar = cropped;
    }
    form.post(route("pets.update", form._id), {
        onSuccess: () => {
            usePetAI(props.pet, { name: "edited", oldPet: startData, newPet: props.pet });
            useRoute({ name: "pets.index" });
        },
        onError: () => {
            useRoute({ name: "pets.edit", params: props.pet._id });
        },
    });
};

function deleteAvatar() {
    form.avatar = null;
}

const deleteForm = useForm({
    _id: props.pet._id,
});

const confirmingPetDeletion = ref(false);

const confirmPetDeletion = () => {
    confirmingPetDeletion.value = true;
};

function deletePet() {
    closeModal();
    deleteForm.delete(route("pets.destroy", props.pet._id), {
        onSuccess: () => {
            useRoute({ name: "pets.index" });
        },
    });
}

const closeModal = () => {
    confirmingPetDeletion.value = false;
};
</script>

<template>
    <Card>
        <form @submit.prevent="submit">

            <InputAvatar v-show="form.species || newAvatar" ref="inputAvatar"
                         :pet="form"
                         @cropped="(cropped) => saveWithCrop(cropped)"
                         @delete="deleteAvatar()"
                         @dirty="newAvatar = true"
            />
            <InputError :message="form.errors.newAvatar" class="mb-2 text-center"/>

            <div v-if="form.name || form.species" class="mb-3">
                <InputLabel for="name" value="Pet Name"/>

                <InputText
                        id="name"
                        v-model="form.name"
                        autocomplete="off"
                        class="mt-1 block w-full"
                        required
                        type="text"
                />

                <InputError :message="form.errors.name" class="mt-2"/>
            </div>

            <div class="flex flex-wrap gap-2 mb-3">
                <div class="grow min-w-1/2">
                    <InputLabel for="species" value="Species"/>

                    <InputButtons id="species" v-model="form.species" :options="meta.species" class="gap-2"/>

                    <InputError :message="form.errors.species" class="mt-2"/>
                </div>

                <div v-if="form.species || form.sex" class="w-1/2">
                    <InputLabel for="sex" value="Sex"/>

                    <InputButtons id="sex" v-model="form.sex" :options="meta.sexes" class="gap-2"/>

                    <InputError :message="form.errors.sex" class="mt-2"/>
                </div>
            </div>

            <div v-if="form.birth_date || (form.species && form.sex)" class="flex justify-between gap-2 mb-3">

                <div class="grow min-w-1/2">

                    <InputLabel for="age" value="Age"/>

                    <BirthdayCalc v-model="form.birth_date" :autofocus="false" :maxAge="maxAge"/>

                </div>

                <div v-if="form.birth_date" class="w-1/2">

                    <InputLabel for="birth_date" value="Birth Date"/>

                    <InputText
                            id="birth_date"
                            v-model="form.birth_date"
                            :max="maxBirthday"
                            :min="minBirthday"
                            autocomplete="no"
                            class="mt-1 block w-full"
                            required
                            type="date"
                    />

                    <InputError :message="form.errors.birth_date" class="mt-2"/>
                </div>
            </div>

            <div v-if="form.weight || (form.species && form.sex && form.birth_date)" class="">

                <InputLabel class="w-full" for="weight" value="Weight"/>

                <div class="flex items-center gap-3 mt-1">
                    <div class="w-[70px]">
                        <InputText
                                id="weight"
                                v-model="form.weight"
                                :max="maxWeight"
                                autocomplete="no"
                                class="block w-full"
                                min="1"
                                type="number"
                        />
                    </div>
                    <div class="">lbs.</div>
                    <div class="grow">
                        <InputText
                                id="weight"
                                v-model="form.weight"
                                :max="maxWeight"
                                autocomplete="no"
                                class="block w-full accent-lt"
                                min="1"
                                type="range"
                        />
                    </div>
                </div>

                <InputError :message="form.errors.weight" class="w-full"/>
            </div>

            <div class="flex items-stretch justify-center mt-8 gap-4">
                <ButtonDefault :class="{ 'opacity-25': form.processing }"
                               :disabled="form.processing"
                               @click.prevent="useRoute({ name: 'pets.index' })"
                >
                    Cancel
                </ButtonDefault>
                <ButtonDefault :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                               @click.prevent="confirmPetDeletion">Delete
                </ButtonDefault>
                <ButtonPrimary
                        v-if="(form.isDirty || newAvatar) && form.name && form.species && form.sex && form.birth_date"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                >
                    Save
                </ButtonPrimary>
            </div>
        </form>
    </Card>

    <ModalConfirm :show="confirmingPetDeletion" @closeModal="closeModal" @confirm="deletePet">
        Are you sure you want to delete this pet? All pet data will be lost permanently.
    </ModalConfirm>
</template>
