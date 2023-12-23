<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
const props = defineProps({
    data: {
        type: Object,
    },
});
</script>

<template>
    <div class="flex flex-col justify-center items-center sm:flex-row sm:flex-wrap gap-6">
        <div class="w-[325px] flex p-2 justify-start bg-white shadow-md overflow-hidden gap-2 sm:rounded-lg" v-for="pet in data.pets">
            <img class="w-[100px] h-[100px] rounded-full" :src="pet.image + '?t=' + Math.ceil(Math.random()*10000)" :alt="pet.name" />
            <div>
                <div class="font-bold text-xl">{{ pet.name }}</div>
                <div> {{ pet.species }}</div>
                <div class="my-1">
                    <SecondaryButton><font-awesome-icon icon="bowl-rice" /></SecondaryButton>
                    <SecondaryButton><font-awesome-icon icon="tree" /></SecondaryButton>
                    <SecondaryButton><font-awesome-icon icon="baseball" /></SecondaryButton>
                    <SecondaryButton><font-awesome-icon icon="car-side" /></SecondaryButton>
                    <SecondaryButton><font-awesome-icon icon="kit-medical" /></SecondaryButton>
                </div>
            </div>
        </div>
    </div>
</template>



