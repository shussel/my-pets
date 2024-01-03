<script setup>
import {reactive, watch, onMounted} from 'vue';
import TextInput from "@/Components/TextInput.vue";
import SelectButtons from "@/Components/SelectButtons.vue";
import moment from "moment";

const props = defineProps({
    modelValue: {
        type: String,
        required: true,
    },
});
const emit = defineEmits(['update:modelValue']);

const ageOptions = [
    [
        {label: 'year', value: 'years'},
        {label: 'month', value: 'months'},
        {label: 'week', value: 'weeks'},
        {label: 'day', value: 'days'},
    ],
    [
        {label: 'years', value: 'years'},
        {label: 'months', value: 'months'},
        {label: 'weeks', value: 'weeks'},
        {label: 'days', value: 'days'},
    ]
]

function ageFromBirthday(birthday) {
    let birthdayAge = {count: null, units: null}
    if (birthday) {
        if (birthdayAge.count = moment().diff(birthday, 'years')) {
            birthdayAge.units = 'years'
        } else if (birthdayAge.count = moment().diff(birthday, 'months')) {
            birthdayAge.units = 'months'
        } else if (birthdayAge.count = moment().diff(birthday, 'weeks')) {
            birthdayAge.units = 'weeks'
        } else if (birthdayAge.count = moment().diff(birthday, 'days')) {
            birthdayAge.units = 'days'
        }
        birthdayAge.count = String(birthdayAge.count);
    }
    return birthdayAge;
}

const age = reactive(ageFromBirthday(props.modelValue || null));

function birthdayFromAge(newAge) {
    if (!newAge.count || !newAge.units) {
        return null;
    } else {
        return moment().subtract(newAge.count, newAge.units).format('YYYY-MM-DD')
    }
}

let ageChanged = false;

watch(age, (newAge) => {
    if (!birthdayChanged) {
        ageChanged = true;
        emit('update:modelValue', birthdayFromAge(newAge))
    }
    birthdayChanged = false;
})

let birthdayChanged = false;

watch(() => props.modelValue, (newBirthday) => {
    if (!ageChanged) {
        const newAge = ageFromBirthday(newBirthday)
        if (age.count !== newAge.count) {
            birthdayChanged = true
            age.count = newAge.count
        }
        if (age.units !== newAge.units) {
            birthdayChanged = true
            age.units = newAge.units
        }
    }
    ageChanged = false;
})
</script>

<template>
    <div class="flex justify-start">
        <TextInput
            id="age"
            type="number"
            class="mt-1 w-[53px] px-2"
            v-model="age.count"
            autocomplete=""
            autofocus
        />
        <SelectButtons class="grow gap-1" v-model="age.units" :key="age.units"
                       :options="age.count > 1 ? ageOptions[1] : ageOptions[0]"/>
    </div>
</template>
