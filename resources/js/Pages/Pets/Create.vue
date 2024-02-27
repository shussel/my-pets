<script setup>
import { toRaw, computed, ref } from "vue";
import ObjectID from "bson-objectid";
import { useForm } from "@inertiajs/vue3";
import Card from "@/Components/Card.vue";
import InputText from "@/Components/InputText.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputButtons from "@/Components/InputButtons.vue";
import InputAvatar from "@/Components/InputAvatar.vue";
import BirthdayCalc from "@/Components/BirthdayCalc.vue";
import ButtonDefault from "@/Components/ButtonDefault.vue";
import ButtonPrimary from "@/Components/ButtonPrimary.vue";
import usePageTitle from "@/Composables/usePageTitle.js";
import usePetAI from "@/Composables/usePetAI.js";
import useRoute from "@/Composables/useRoute.js";

const props = defineProps({
    meta: {
        type: Object,
    },
});

usePageTitle("Add Pet");

const form = useForm({
    _id: null,
    name: "",
    species: "",
    sex: "",
    weight: null,
    birth_date: "",
    avatar: null,
    newAvatar: null,
});

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

const submit = () => {
    inputAvatar.value.crop();
};
const saveWithCrop = (cropped) => {
    if (cropped) {
        form.newAvatar = cropped;
    }
    form._id = ObjectID().toHexString();
    form.post(route("pets.store"), {
        onSuccess: () => {
            usePetAI(form.data(), { name: "added", pet: form.data() });
            useRoute({ name: "pets.index" });
        },
        onError: () => {
            useRoute({ name: "pets.create" });
        },
    });
};

const keepCropper = ref(false);

</script>

<template>
    <Card>
        <form @submit.prevent="submit">

            <InputAvatar v-if="form.species || keepCropper" ref="inputAvatar"
                         :pet="form"
                         @cropped="(cropped) => saveWithCrop(cropped)"
                         @dirty="keepCropper = true"
            />
            <InputError :message="form.errors.newAvatar" class="mb-2 text-center"/>

            <div v-if="form.name || form.species" class="mb-3">
                <InputLabel for="name" value="Pet Name"/>

                <InputText
                        id="name"
                        v-model="form.name"
                        autocomplete="off"
                        autofocus
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

                    <BirthdayCalc id="age" v-model="form.birth_date" :maxAge="maxAge"/>

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

            <div v-if="form.species && form.sex && form.birth_date" class="">

                <InputLabel class="w-full" for="weight" value="Weight"/>

                <div class="flex items-center gap-3 mt-1">
                    <div class="w-[70px]">
                        <InputText
                                id="weight"
                                v-model="form.weight"
                                :max="maxWeight"
                                autocomplete="no"
                                autofocus
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

            <div class="flex items-center justify-center mt-8 gap-4">
                <ButtonDefault :class="{ 'opacity-25': form.processing }"
                               :disabled="form.processing"
                               @click.prevent="useRoute({ name: 'pets.index' })"
                >
                    Cancel
                </ButtonDefault>
                <ButtonPrimary v-if="form.name && form.species && form.sex && form.birth_date"
                               :class="{ 'opacity-25': form.processing }"
                               :disabled="form.processing"
                >
                    Save
                </ButtonPrimary>
            </div>
        </form>
    </Card>
</template>
