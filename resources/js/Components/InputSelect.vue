<script setup>
import {onMounted, ref} from "vue";

const props = defineProps({
    modelValue: {
        type: String,
        required: true,
    },
    options: {
        type: Object
    },
    placeholder: {
        type: String,
        default: "select",
    }
});

defineEmits(["update:modelValue"]);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({focus: () => input.value.focus()});
</script>

<template>
    <select
            ref="input"
            :value="modelValue"
            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            @input="$emit('update:modelValue', $event.target.value)"
    >
        <option disabled hidden selected value="">{{ placeholder }}</option>
        <option v-for="option in options" :value="option.value">{{ option.label }}</option>
    </select>
</template>
