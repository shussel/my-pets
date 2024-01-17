<script setup>
import {onMounted, ref} from "vue";

defineProps({
    modelValue: {
        type: String,
        required: true,
    },
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
    <input
            ref="input"
            :value="modelValue"
            class="rounded-md shadow-sm
            border-slate-300
                dark:border-slate-600
            bg-white
                dark:bg-slate-900
            focus:border-lt/50 focus:ring-lt/50"
            @input="$emit('update:modelValue', $event.target.value)"
    />
</template>
