<script setup>
import {ref, toRaw, toRef, onMounted, defineEmits} from "vue";
import FAIcon from '@/Components/FAIcon.vue';
import {Cropper, CircleStencil} from 'vue-advanced-cropper'

const props = defineProps({
  imageData: {
    type: Blob,
  },
  species: {
    type: String,
  },
});

const imageUrl = ref(null)

const emit = defineEmits(['input', 'update:imageData', 'save']);

const fileInput = ref(null)

function chooseImage() {
  fileInput.value.click()
}

function onSelectFile() {
  const input = fileInput.value
  const files = input.files
  if (files && files[0]) {
    const reader = new FileReader
    reader.onload = e => {
      imageUrl.value = e.target.result
    }
    reader.readAsDataURL(files[0])
    emit('update', files[0])
  }
}

const cropper = ref(null)

const coordinates = ref({
  width: 0,
  height: 0,
  left: 0,
  top: 0,
})

defineExpose({
  crop,
});

function crop() {
  const {coordinates: newCoordinates, canvas,} = cropper.value.getResult();
  coordinates.value = newCoordinates;
  // imageUrl.value = canvas.toDataURL();
  canvas.toBlob(blob => {
    emit('save', blob)
  }, 'image/jpeg');
}

</script>

<template>
  <div class="mb-1 h-[200px] overflow-hidden">
    <Cropper v-show="imageUrl" class="cropper base-image-input w-full h-auto"
             :src="imageUrl" alt="Profile Image" @click="chooseImage" ref="cropper"
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
        maxHeight: 1024,
        maxWidth: 1024,
      }"
    />
    <div class="text-center placeholder"
         v-if="!imageUrl"
         @click="chooseImage"
    >
      <FAIcon :name="species"
              class="bg-blue-100 text-white w-[152px] h-[152px] rounded-full p-6"/>
    </div>
    <input
        class="file-input hidden"
        ref="fileInput"
        type="file"
        @input="onSelectFile"
        accept="image/jpeg"/>
    >
  </div>
</template>

<style>
.base-image-input {
  display: block;
  cursor: pointer;
}

.placeholder {
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.placeholder:hover {
  background: #E0E0E0;
}

.vue-advanced-cropper {
  text-align: center;
  position: relative;
  user-select: none;
  max-height: 100%;
  max-width: 100%;
  direction: ltr;
}

.vue-advanced-cropper__stretcher {
  pointer-events: none;
  position: relative;
  max-width: 100%;
  max-height: 100%;
}

.vue-advanced-cropper__image {
  user-select: none;
  position: absolute;
  transform-origin: center;
  max-width: none !important;
}

.vue-advanced-cropper__background, .vue-advanced-cropper__foreground {
  opacity: 1;
  background: white;
  transform: translate(-50%, -50%);
  position: absolute;
  top: 50%;
  left: 50%;
}

.vue-advanced-cropper__foreground {
  opacity: 1;
}

.vue-advanced-cropper__boundaries {
  opacity: 1;
  transform: translate(-50%, -50%);
  position: absolute;
  left: 50%;
  top: 50%;
}

.vue-advanced-cropper__cropper-wrapper {
  width: 100%;
  height: 100%;
}

.vue-advanced-cropper__image-wrapper {
  overflow: hidden;
  position: absolute;
  width: 100%;
  height: 100%;
}

.vue-advanced-cropper__stencil-wrapper {
  position: absolute;
}

.vue-handler-wrapper {
  position: absolute;
  transform: translate(-50%, -50%);
  width: 30px;
  height: 30px;
}

.vue-handler-wrapper__draggable {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.vue-handler-wrapper--west-north {
  cursor: nw-resize;
}

.vue-handler-wrapper--north {
  cursor: n-resize;
}

.vue-handler-wrapper--east-north {
  cursor: ne-resize;
}

.vue-handler-wrapper--east {
  cursor: e-resize;
}

.vue-handler-wrapper--east-south {
  cursor: se-resize;
}

.vue-handler-wrapper--south {
  cursor: s-resize;
}

.vue-handler-wrapper--west-south {
  cursor: sw-resize;
}

.vue-handler-wrapper--west {
  cursor: w-resize;
}

.vue-handler-wrapper--disabled {
  cursor: auto;
}

.vue-line-wrapper {
  background: none;
  position: absolute;
  display: flex;
  align-items: center;
  justify-content: center;
}

.vue-line-wrapper--north, .vue-line-wrapper--south {
  height: 12px;
  width: 100%;
  left: 0;
  transform: translateY(-50%);
}

.vue-line-wrapper--north {
  top: 0;
  cursor: n-resize;
}

.vue-line-wrapper--south {
  top: 100%;
  cursor: s-resize;
}

.vue-line-wrapper--east, .vue-line-wrapper--west {
  width: 12px;
  height: 100%;
  transform: translateX(-50%);
  top: 0;
}

.vue-line-wrapper--east {
  left: 100%;
  cursor: e-resize;
}

.vue-line-wrapper--west {
  left: 0;
  cursor: w-resize;
}

.vue-line-wrapper--disabled {
  cursor: auto;
}

.vue-bounding-box {
  position: relative;
  height: 100%;
  width: 100%;
}

.vue-bounding-box__handler {
  position: absolute;
}

.vue-bounding-box__handler--west-north {
  left: 0;
  top: 0;
}

.vue-bounding-box__handler--north {
  left: 50%;
  top: 0;
}

.vue-bounding-box__handler--east-north {
  left: 100%;
  top: 0;
}

.vue-bounding-box__handler--east {
  left: 100%;
  top: 50%;
}

.vue-bounding-box__handler--east-south {
  left: 100%;
  top: 100%;
}

.vue-bounding-box__handler--south {
  left: 50%;
  top: 100%;
}

.vue-bounding-box__handler--west-south {
  left: 0;
  top: 100%;
}

.vue-bounding-box__handler--west {
  left: 0;
  top: 50%;
}

.vue-draggable-area {
  position: relative;
}

.vue-preview-result {
  overflow: hidden;
  box-sizing: border-box;
  position: absolute;
  height: 100%;
  width: 100%;
}

.vue-preview-result__wrapper {
  position: absolute;
}

.vue-preview-result__image {
  pointer-events: none;
  position: relative;
  user-select: none;
  transform-origin: center;
  max-width: none !important;
}

.vue-simple-handler {
  display: block;
  background: white;
  height: 10px;
  width: 10px;
}

.vue-simple-line {
  background: none;
  transition: border 0.5s;
  border-color: rgba(255, 255, 255, 0.3);
  border-width: 0;
  border-style: solid;
}

.vue-simple-line--south, .vue-simple-line--north {
  height: 0;
  width: 100%;
}

.vue-simple-line--east, .vue-simple-line--west {
  height: 100%;
  width: 0;
}

.vue-simple-line--east {
  border-right-width: 1px;
}

.vue-simple-line--west {
  border-left-width: 1px;
}

.vue-simple-line--south {
  border-bottom-width: 1px;
}

.vue-simple-line--north {
  border-top-width: 1px;
}

.vue-simple-line--hover {
  opacity: 1;
  border-color: white;
}

.vue-preview {
  overflow: hidden;
  box-sizing: border-box;
  position: relative;
}

.vue-preview--fill {
  width: 100%;
  height: 100%;
  position: absolute;
}

.vue-preview__wrapper {
  position: absolute;
  height: 100%;
  width: 100%;
}

.vue-preview__image {
  pointer-events: none;
  position: absolute;
  user-select: none;
  transform-origin: center;
  max-width: none !important;
}

.vue-rectangle-stencil {
  position: absolute;
  height: 100%;
  width: 100%;
  box-sizing: border-box;
}

.vue-rectangle-stencil__preview {
  position: absolute;
  width: 100%;
  height: 100%;
}

.vue-rectangle-stencil--movable {
  cursor: move;
}

.vue-circle-stencil {
  position: absolute;
  height: 100%;
  width: 100%;
  box-sizing: content-box;
  cursor: move;
}

.vue-circle-stencil__preview {
  border-radius: 50%;
  position: absolute;
  width: 100%;
  height: 100%;
}

.vue-circle-stencil--movable {
  cursor: move;
}
</style>