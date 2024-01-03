<script setup>
import {ref} from 'vue';
import SelectButton from "@/Components/SelectButton.vue";
import FAIcon from '@/Components/FAIcon.vue';

const props = defineProps({
    options: {
        type: Object,
    },
    modelValue: {
        type: String,
        required: true,
    },
});

const selected = ref(props.modelValue);

const emit = defineEmits(['update:modelValue']);

function clickItem(item) {
    if (item === selected.value) {
        selected.value = null
    } else {
        selected.value = item
    }
    emit('update:modelValue', selected.value)
}

</script>

<template>
    <div
        class="flex flex-wrap gap-2 justify-center items-center focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
        <SelectButton v-for="item in options"
                      class="w-auto"
                      :selected="item.value === selected"
                      :selection="selected"
                      @click.prevent="clickItem(item.value)"
        >
            <FAIcon :name="item.value"/>
            {{ item.label }}
        </SelectButton>
    </div>
</template>
