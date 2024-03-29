<script setup>
import {computed, ref} from "vue";
import {Cropper, CircleStencil} from "vue-advanced-cropper";
import PetImage from "@/Components/PetImage.vue";
import ButtonDelete from "@/Components/ButtonDelete.vue";
import ModalConfirm from "@/Components/ModalConfirm.vue";
import "vue-advanced-cropper/dist/style.css";

const props = defineProps({
    pet: {
        type: Object,
    },
});

const emit = defineEmits(["cropped", "dirty", "delete"]);

// file input control
const fileInput = ref(null);

// open file input dialog
function chooseImage() {
    if (!props.pet || !props.pet.avatar) {
        fileInput.value.click();
    }
}

// blobUrl of image to be cropped
const cropImageUrl = ref(null);

// read file input into crop image
function onSelectFile() {
    const input = fileInput.value;
    const files = input.files;
    if (files && files[0]) {
        cropImageUrl.value = URL.createObjectURL(files[0]);
        emit("dirty");
    }
}

// cropper component
const cropper = ref(null);
const coordinates = ref({
    width: 0,
    height: 0,
    left: 0,
    top: 0,
});
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
            emit("cropped", blob);
            URL.revokeObjectURL(cropImageUrl.value);
        }, "image/jpeg");
        // return no crop
    } else {
        emit("cropped", false);
    }
}

// cropper instructions
const message = computed(() => {
    if (cropImageUrl.value) {
        return "drag and pinch to adjust image";
    } else if (!props.pet || !props.pet.avatar) {
        return "click to add image";
    } else {
        return null;
    }
});

// delete avatar
const showDelete = computed(() => {
    return ((props.pet && props.pet.avatar) || cropImageUrl.value);
});

function deleteAvatar() {
    closeModal();
    if (cropImageUrl.value) {
        // clear cropped image
        cropImageUrl.value = null;
    } else if (props.pet && props.pet.avatar) {
        // delete saved image
        emit("delete");
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
    <div class="h-[200px] w-full overflow-hidden relative">

        <Cropper v-show="cropImageUrl"
                 ref="cropper"
                 :canvas="{
        minHeight: 0,
        minWidth: 0,
        maxHeight: 400,
        maxWidth: 400,
      }" :resize-image="{
        adjustStencil: false
      }" :src="cropImageUrl"
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
                 alt="Profile Image"
                 class="border-4 border-dashed border-lt/50 cropper block cursor-pointer base-image-input w-full h-auto"
                 image-restriction="stencil"
        />

        <div
                v-show="!cropImageUrl"
                class="text-center placeholder w-[200px] h-[200px] relative flex justify-center items-center mx-auto">
            <PetImage :pet="pet" class="w-[200px] h-[200px] hover:bg-lt/50" @click="chooseImage"/>
            <ButtonDelete v-show="pet.avatar" class="absolute bottom-0 right-1 z-50" title="Delete Image"
                          @click="confirmAvatarDeletion"/>
        </div>

        <input
                ref="fileInput"
                accept="image/jpeg"
                class="file-input hidden"
                type="file"
                @input="onSelectFile"
        />

        <ButtonDelete v-show="cropImageUrl" class="absolute bottom-2 right-2 z-50" title="Delete Image"
                      @click="confirmAvatarDeletion"/>

    </div>

    <div class="text-xs text-center mb-2">{{ message }}</div>

    <ModalConfirm :show="confirmingAvatarDeletion" @closeModal="closeModal" @confirm="deleteAvatar">Are you sure you
        want to delete this image?
    </ModalConfirm>
</template>

<style>
.vue-advanced-cropper__background, .vue-advanced-cropper__foreground {
    opacity: 1;
    background: white;
}

.dark .vue-advanced-cropper__background, .dark .vue-advanced-cropper__foreground {
    background: #334155;
}
</style>