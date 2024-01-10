<script setup>
import ButtonDefault from "@/Components/ButtonDefault.vue";
import FAIcon from "@/Components/FAIcon.vue";

const props = defineProps({
    pet: {
        type: Object,
    },
});
</script>

<template>
    <div class="mt-4 flex justify-between">
        <ButtonDefault class="w-[50px] h-[50px]">
            <FAIcon class="text-2xl" name="food"/>
        </ButtonDefault>
        <ButtonDefault class="w-[50px] h-[50px]">
            <FAIcon class="text-2xl" name="poop"/>
        </ButtonDefault>
        <ButtonDefault class="w-[50px] h-[50px]">
            <FAIcon class="text-2xl" name="play"/>
        </ButtonDefault>
        <ButtonDefault class="w-[50px] h-[50px]">
            <FAIcon class="text-2xl" name="car"/>
        </ButtonDefault>
        <ButtonDefault class="w-[50px] h-[50px]">
            <FAIcon class="text-2xl" name="help"/>
        </ButtonDefault>
    </div>
</template>
