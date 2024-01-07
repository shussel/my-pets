<script setup>
import {computed, ref, toRaw} from "vue";
import {useForm} from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SelectButtons from "@/Components/SelectButtons.vue";
import BirthdayCalc from "@/Components/BirthdayCalc.vue";
import TextInput from '@/Components/TextInput.vue';
import InputAvatar from "@/Components/InputAvatar.vue";
import ModalConfirm from '@/Components/ModalConfirm.vue';

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

const emit = defineEmits(['nav']);

const form = useForm({
  _id: props.pet._id,
  name: props.pet.name,
  species: props.pet.species,
  sex: props.pet.sex,
  weight: props.pet.weight,
  birth_date: props.pet.birth_date,
  avatar: props.pet.avatar,
  newAvatar: null,
  _method: 'put'
});

const maxAge = computed(() => {
  if (!form.species) {
    return 20;
  }
  return toRaw(props.meta.species).filter(function (specie) {
    return specie.value === form.species;
  })[0]['maxAge'];
})

const maxBirthday = computed(() => {
  return new Date().toISOString().substr(0, 10);
})

const minBirthday = computed(() => {
  return new Date(new Date().setFullYear(new Date().getFullYear() - maxAge.value)).toISOString().substr(0, 10)
})

const maxWeight = computed(() => {
  if (!form.species) {
    return 100;
  }
  return toRaw(props.meta.species).filter(function (specie) {
    return specie.value === form.species;
  })[0]['maxWeight'];
})

const inputAvatar = ref(null)
const newAvatar = ref(false)
const submit = () => {
  inputAvatar.value.crop()
};
const saveWithCrop = (cropped) => {
  disableButtons.value = true;
  if (cropped) {
    form.newAvatar = cropped;
  }
  form.post(route('pets.update', form._id), {
    onSuccess: () => {
      emit('nav', 'pets.index')
      disableButtons.value = false;
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
const disableButtons = ref(false);

const confirmPetDeletion = () => {
  confirmingPetDeletion.value = true;
  disableButtons.value = true;
};

function deletePet() {
  closeModal();
  deleteForm.delete(route('pets.destroy', props.pet._id), {
    onSuccess: () => {
      emit('nav', 'pets.index')
    },
  });
}

const closeModal = () => {
  confirmingPetDeletion.value = false;
  disableButtons.value = false;
};
</script>

<template>
  <div
      class="flex flex-col justify-start items-stretch w-full sm:max-w-md p-4 sm:p-8 bg-white shadow-md overflow-hidden sm:rounded-lg mx-auto"
  >
    <form @submit.prevent="submit" class="border px-4 py-2 rounded-lg">

      <InputAvatar v-show="form.species || newAvatar" :pet="form"
                   @cropped="(cropped) => saveWithCrop(cropped)"
                   @dirty="newAvatar = true"
                   @delete="deleteAvatar()"
                   ref="inputAvatar"
      />

      <div v-if="form.name || form.species" class="mb-3">
        <InputLabel for="name" value="Pet Name"/>

        <TextInput
            id="name"
            type="text"
            class="mt-1 block w-full"
            v-model="form.name"
            required
            autocomplete="off"
        />

        <InputError class="mt-2" :message="form.errors.name"/>
      </div>

      <div class="flex flex-wrap gap-2 mb-3">
        <div class="grow min-w-1/2">
          <InputLabel for="species" value="Species"/>

          <SelectButtons v-model="form.species" :options="meta.species"/>

          <InputError class="mt-2" :message="form.errors.species"/>
        </div>

        <div v-if="form.species || form.sex" class="w-1/2">
          <InputLabel for="sex" value="Sex"/>

          <SelectButtons v-model="form.sex" :options="meta.sexes"/>

          <InputError class="mt-2" :message="form.errors.sex"/>
        </div>
      </div>

      <div v-if="form.birth_date || (form.species && form.sex)" class="flex justify-between gap-2 mb-3">

        <div class="grow min-w-1/2">

          <InputLabel for="age" value="Age"/>

          <BirthdayCalc v-model="form.birth_date" :maxAge="maxAge"/>

        </div>

        <div v-if="form.birth_date" class="w-1/2">

          <InputLabel for="birth_date" value="Birth Date"/>

          <TextInput
              id="birth_date"
              type="date"
              class="mt-1 block w-full"
              v-model="form.birth_date"
              :max="maxBirthday"
              :min="minBirthday"
              required
              autocomplete=""
          />

          <InputError class="mt-2" :message="form.errors.birth_date"/>
        </div>
      </div>

      <div v-if="form.weight || (form.species && form.sex && form.birth_date)" class="">

        <InputLabel class="w-full" for="weight" value="Weight"/>

        <div class="flex items-center gap-3 mt-1">
          <div class="w-[70px]">
            <TextInput
                id="weight"
                type="number"
                class="block w-full"
                v-model="form.weight"
                min="1"
                :max="maxWeight"
                autocomplete=""
            />
          </div>
          <div class="">lbs.</div>
          <div class="grow">
            <TextInput
                id="weight"
                type="range"
                min="1"
                :max="maxWeight"
                class="block w-full color-white"
                v-model="form.weight"
                autocomplete=""
            />
          </div>
        </div>

        <InputError class="w-full" :message="form.errors.weight"/>
      </div>

      <div class="flex items-center justify-center mt-8 gap-4">
        <SecondaryButton @click.prevent="emit('nav', 'pets.index')"
                         :class="{ 'opacity-25': form.processing }"
                         :disabled="disableButtons"
        >
          Cancel
        </SecondaryButton>
        <SecondaryButton class="m-2" @click="confirmPetDeletion" :class="{ 'opacity-25': disableButtons }"
                         :disabled="disableButtons">Delete
        </SecondaryButton>
        <PrimaryButton v-if="(form.isDirty || newAvatar) && form.name && form.species && form.sex && form.birth_date"
                       :class="{ 'opacity-25': disableButtons }"
                       :disabled="disableButtons"
        >
          Save
        </PrimaryButton>
      </div>
    </form>
  </div>

  <ModalConfirm :show="confirmingPetDeletion" @confirm="deletePet()" @closeModal="closeModal">Are you sure you
    want to delete this pet? All pet data will be lost permanently.
  </ModalConfirm>
</template>
