<script setup>
import { computed, watchEffect } from "vue";
import usePetAI from "@/Composables/usePetAI.js";
import useRoute from "@/Composables/useRoute.js";

const props = defineProps({
    pet: {
        type: Object,
        required: true,
    }
});

const { petState, clearPetState } = usePetAI(props.pet);

const message = computed(() => {
    return petState.value["speak"] ? petState.value["speak"] : (petState.value["think"] ? petState.value["think"] : petState.value["status"]);
});

const messageClass = computed(() => {
    return (petState.value["speak"] ?
        // speech bubble
        "ml-5 relative rounded-2xl text-slate-700 text-lg text-center font-medium tracking-wide dark:text-slate-100 bg-blue-100 dark:bg-blue-300/60 after:content-[''] after:top-[40%] after:-left-6 after:border-solid after:border-y-8 after:border-l-0 after:border-r-[24px] after:border-x-blue-100 after:border-y-transparent dark:after:border-x-blue-300/60 after:absolute after:block after:w-0 after:-mt-2" :
        (petState.value["think"] ?
            // thought bubble
            "ml-8 relative rounded-3xl text-slate-700 text-lg text-center font-medium tracking-wide dark:text-slate-50 bg-slate-200 dark:bg-slate-100/40 before:content-[''] before:top-[40%] before:-left-9 before:h-3.5 before:w-2.5 before:bg-slate-200 before:rounded-[50%] before:absolute before:block before:-mt-2 dark:before:bg-slate-100/40 after:content-[''] after:top-[38%] after:-left-5 after:h-5 after:w-3.5 after:bg-slate-200 after:rounded-[50%] after:absolute after:block after:-mt-2 dark:after:bg-slate-100/40" :
            // status message
            "ml-2 border border-slate-200 dark:border-slate-500 rounded-lg"));
});

watchEffect(() => {
    if (petState.value["speak"]) {
        clearPetState(props.pet._id, "speak");
    } else if (petState.value["think"]) {
        clearPetState(props.pet._id, "think");
    }
});

</script>

<template>
    <div :class="[messageClass, {'underline cursor-pointer': message?.route}]"
         class="mt-3 p-3 flex justify-center items-center h-[56px]"
         @click="useRoute(message?.route)">
        <div>{{ message?.text }}</div>
    </div>
</template>
