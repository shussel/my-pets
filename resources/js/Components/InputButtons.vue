<script setup>
import { ref, computed } from "vue";
import ButtonSelect from "@/Components/ButtonSelect.vue";
import FAIcon from "@/Components/FAIcon.vue";

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

const emit = defineEmits(["update:modelValue"]);

const singleChoice = computed(() => {
    return props.options.length === 1 ? props.options[0].value : null;
});

if (singleChoice.value) {
    clickItem(singleChoice.value);
}

function clickItem(item) {
    if (item === selected.value) {
        if (!singleChoice.value) {
            selected.value = null;
        }
    } else {
        selected.value = item;
    }
    emit("update:modelValue", selected.value);
}
</script>

<template>
    <div class="mt-1 flex flex-wrap justify-center items-center focus:border-lt/50 focus:ring-lt/50 rounded-md shadow-sm">
        <ButtonSelect v-for="item in options"
                      :selected="item.value === selected"
                      :selection="selected"
                      class="w-auto"
                      @click.prevent="clickItem(item.value)"
                      :singleChoice="singleChoice"
        >
            <FAIcon :color="item.color" :name="item.value" class="mr-1"/>
            {{ item.label }}
        </ButtonSelect>
    </div>
</template>
