<script setup>
import {Head} from "@inertiajs/vue3";
import ButtonBone from "@/Components/ButtonBone.vue";
import Layout from "@/Layouts/GuestLayout.vue";
import useRoute from "@/Composables/useRoute.js";

defineOptions({layout: Layout});

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
        <div v-if="canRegister">
            <h2 class="block text-center text-lg">New Users</h2>
            <ButtonBone @click="useRoute({ name:'register'})">Register</ButtonBone>
        </div>
        <div v-if="canLogin">
            <h2 class="block text-center mt-3 text-lg">Existing Users</h2>
            <ButtonBone class="mb-4" @click="useRoute({ name:'login'})">Log In</ButtonBone>
        </div>
    </div>
</template>



