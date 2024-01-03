<script setup>
import {ref, reactive, watch} from 'vue';
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
    let newAge = {count: null, units: null}
    if (birthday) {
        if (newAge.count = moment().diff(birthday, 'years')) {
            newAge.units = 'years'
        } else if (newAge.count = moment().diff(birthday, 'months')) {
            newAge.units = 'months'
        } else if (newAge.count = moment().diff(birthday, 'weeks')) {
            newAge.units = 'weeks'
        } else if (newAge.count = moment().diff(birthday, 'days')) {
            newAge.units = 'days'
        }
        newAge.count = String(newAge.count);
    }
    console.log('from bd', birthday, newAge)
    return newAge;
}

const age = reactive(ageFromBirthday(props.modelValue.value || null));
console.log('initial', age)

function birthdayFromAge(newAge) {
    let birthday
    if (!newAge.count || !newAge.units) {
        birthday = null;
    } else {
        birthday = moment().subtract(newAge.count, newAge.units).format('YYYY-MM-DD')
    }
    console.log('birthday set', birthday);
    return birthday
}

let watching = false;

watch(age, (newAge) => {
    if (!watching) {
        console.log('age changed', newAge)
        watching = true;
        emit('update:modelValue', birthdayFromAge(newAge))
        watching = false;
    }
})

watch(() => props.modelValue, (newBirthday, oldBirthday) => {
    if (!watching) {
        console.log('bd changed', oldBirthday, newBirthday)
        if (newBirthday !== oldBirthday) {
            if (newBirthday || (age.count && age.units)) {
                const newAge = ageFromBirthday(newBirthday)
                if (age.count !== newAge.count) {
                    watching = true;
                    age.count = newAge.count
                    console.log('age count changed', newAge.count)
                }
                if (age.units !== newAge.units) {
                    watching = true;
                    age.units = newAge.units
                    console.log('age units changed', newAge.units)
                }
            }
        }
        watching = false;
    }
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
        <SelectButtons class="grow" v-model="age.units" :options="age.count > 1 ? ageOptions[1] : ageOptions[0]"/>
    </div>
</template>
