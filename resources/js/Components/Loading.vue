<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

const show = ref(false);

router.on("start", () => {
    show.value = true;
});
router.on("finish", () => {
    show.value = false;
});

</script>

<template>
    <Teleport to="body">
        <Transition leave-active-class="duration-200">
            <div v-show="show"
                 class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50 flex justify-center items-center"
                 scroll-region>
                <Transition
                        enter-active-class="ease-out duration-300"
                        enter-from-class="opacity-0"
                        enter-to-class="opacity-100"
                        leave-active-class="ease-in duration-200"
                        leave-from-class="opacity-100"
                        leave-to-class="opacity-0"
                >
                    <div class="fixed inset-0 transform transition-all blur">
                        <div class="absolute inset-0 bg-slate-200 opacity-50"/>
                    </div>
                </Transition>
                <img src="/spinner.svg">
            </div>
        </Transition>
    </Teleport>
</template>
