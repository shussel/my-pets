<template>
    <div class="flex flex-col justify-center items-center sm:flex-row sm:flex-wrap gap-6">
        <div class="w-[325px] p-2 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="flex justify-start gap-2">
                <img class="w-[100px] h-[100px] rounded-full" :src="pet.image + '?t=' + Math.ceil(Math.random()*10000)" :alt="pet.name" />
                <div>
                    <div class="font-bold text-xl">{{ pet.name }}</div>
                    <div> {{ pet.species }}</div>
                    <div> {{ pet.sex }}</div>
                </div>
            </div>
            <div class="mt-4 flex justify-between">
                <SecondaryButton><font-awesome-icon icon="bowl-rice" /></SecondaryButton>
                <SecondaryButton><font-awesome-icon icon="tree" /></SecondaryButton>
                <SecondaryButton><font-awesome-icon icon="baseball" /></SecondaryButton>
                <SecondaryButton><font-awesome-icon icon="car-side" /></SecondaryButton>
                <SecondaryButton><font-awesome-icon icon="kit-medical" /></SecondaryButton>
            </div>
        </div>
    </div>
    <td class="border px-4 py-2">{{ pet.weight }} lbs</td>
    <td class="border px-4 py-2">{{ pet.age }}</td>
    <td class="border px-4 py-2">{{ new Date(pet.birth_date).toLocaleDateString('en-us', { year:"numeric", month:"short", day:"numeric"}) }}</td>
</template>

<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import {toRaw, toRef} from "vue";
const props = defineProps({
    petId: {
        type: String,
    },
    pets: {
        type: Object,
    }
});

const pet = toRef(toRaw(props.pets).filter(function (pet) {
    return pet._id === props.petId;
})[0])

</script>.
