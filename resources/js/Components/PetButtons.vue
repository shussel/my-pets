<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import FAIcon from '@/Components/FAIcon.vue';

const props = defineProps({
    pet: {
        type: Object,
    },
});
</script>

<template>
    <div class="mt-4 flex justify-between">
        <SecondaryButton class="w-[50px] h-[50px]">
            <FAIcon class="text-2xl" name="food"/>
        </SecondaryButton>
        <SecondaryButton class="w-[50px] h-[50px]">
            <FAIcon class="text-2xl" name="poop"/>
        </SecondaryButton>
        <SecondaryButton class="w-[50px] h-[50px]">
            <FAIcon class="text-2xl" name="play"/>
        </SecondaryButton>
        <SecondaryButton class="w-[50px] h-[50px]">
            <FAIcon class="text-2xl" name="car"/>
        </SecondaryButton>
        <SecondaryButton class="w-[50px] h-[50px]">
            <FAIcon class="text-2xl" name="help"/>
        </SecondaryButton>
    </div>
</template>
