<script setup>
import {computed, ref} from "vue";
import {Cropper, CircleStencil} from 'vue-advanced-cropper'
import PetImage from '@/Components/PetImage.vue';
import ButtonDelete from '@/Components/ButtonDelete.vue';
import ModalConfirm from '@/Components/ModalConfirm.vue';
import 'vue-advanced-cropper/dist/style.css';

const props = defineProps({
  pet: {
    type: Object,
  },
});

const emit = defineEmits(['cropped', 'dirty', 'delete']);

// file input control
const fileInput = ref(null)

// open file input dialog
function chooseImage() {
  fileInput.value.click()
}

// blobUrl of image to be cropped
const cropImageUrl = ref(null)

// read file input into crop image
function onSelectFile() {
  const input = fileInput.value
  const files = input.files
  if (files && files[0]) {
    cropImageUrl.value = URL.createObjectURL(files[0])
    emit('dirty')
  }
}

// cropper component
const cropper = ref(null)
const coordinates = ref({
  width: 0,
  height: 0,
  left: 0,
  top: 0,
})
// crop can be triggered from outside component
defineExpose({
  crop,
});

// crop and emit cropped file blob or null
function crop() {
  // return cropped image data
  if (cropImageUrl.value) {
    const {coordinates: newCoordinates, canvas,} = cropper.value.getResult();
    coordinates.value = newCoordinates;
    canvas.toBlob(blob => {
      emit('cropped', blob)
      URL.revokeObjectURL(cropImageUrl.value)
    }, 'image/jpeg');
    // return no crop
  } else {
    emit('cropped', false)
  }
}

// cropper instructions
const message = computed(() => {
  if (cropImageUrl.value) {
    return 'drag and pinch to adjust image'
  } else if (props.pet && props.pet.avatar) {
    return 'click to replace image'
  } else {
    return 'click to add image'
  }
})

// delete avatar
const showDelete = computed(() => {
  return ((props.pet && props.pet.avatar) || cropImageUrl.value)
})

function deleteAvatar() {
  closeModal();
  if (cropImageUrl.value) {
    // clear cropped image
    cropImageUrl.value = null
  } else if (props.pet && props.pet.avatar) {
    // delete saved image
    emit('delete')
  }
}

const confirmingAvatarDeletion = ref(false);
const confirmAvatarDeletion = () => {
  confirmingAvatarDeletion.value = true;
};
const closeModal = () => {
  confirmingAvatarDeletion.value = false;
};
</script>

<template>
  <div class="h-[220px] w-full overflow-hidden relative">

    <Cropper v-show="cropImageUrl" class="cropper block cursor-pointer base-image-input w-full h-auto"
             :src="cropImageUrl" alt="Profile Image" ref="cropper"
             :stencil-component="CircleStencil"
             :stencil-props="{
        handlers: {},
        movable: false,
        resizable: false,
      }"
             :stencil-size="{
        width: 200,
        height: 200
      }"
             :resize-image="{
        adjustStencil: false
      }"
             image-restriction="stencil"
             :canvas="{
        minHeight: 0,
        minWidth: 0,
        maxHeight: 400,
        maxWidth: 400,
      }"
    />

    <div
        class="text-center placeholder w-[220px] h-[220px] relative flex justify-center items-center hover:bg-blue-100 mx-auto"
        v-show="!cropImageUrl">
      <PetImage @click="chooseImage" :pet="pet" class="w-[200px] h-[200px]"/>
    </div>

    <input
        class="file-input hidden"
        ref="fileInput"
        type="file"
        @input="onSelectFile"
        accept="image/jpeg"
    />

    <ButtonDelete v-show="showDelete" @click="confirmAvatarDeletion" class="absolute top-2 right-1 z-50"/>

  </div>

  <div class="text-xs text-center mb-2">{{ message }}</div>

  <ModalConfirm :show="confirmingAvatarDeletion" @confirm="deleteAvatar" @closeModal="closeModal">Are you sure you
    want to delete this image?
  </ModalConfirm>
</template>

<style>
.vue-advanced-cropper__background, .vue-advanced-cropper__foreground {
  opacity: 1;
  background: white;
}
</style>