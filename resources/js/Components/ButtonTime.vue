<script setup>
import ButtonDefault from "@/Components/ButtonDefault.vue";
import FAIcon from "@/Components/FAIcon.vue";
</script>

<template>
    <ButtonDefault class="w-full">
        <div class="flex w-full justify-between py-1">
            <div>+ Add Time</div>
            <div>
                <FAIcon class="text-base" name="clock"/>
            </div>
        </div>
    </ButtonDefault>
</template>