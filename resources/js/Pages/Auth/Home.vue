<script setup>
import {Head} from '@inertiajs/vue3';
import BoneButton from '@/Components/BoneButton.vue';
import Layout from '@/Layouts/GuestLayout.vue';

defineOptions({layout: Layout})

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="Welcome"/>
    <div class="w-1/2 mt-5 mb-6 self-center">
        <div v-if="canLogin">
            <h2 class="text-center p-2">Existing Users</h2>
            <BoneButton @click="$emit('nav','login')" class="mb-4">Log In</BoneButton>
        </div>
        <div v-if="canRegister">
            <h2 class="text-center p-2">New Users</h2>
            <BoneButton @click="$emit('nav','register')">Register</BoneButton>
        </div>
    </div>
</template>



