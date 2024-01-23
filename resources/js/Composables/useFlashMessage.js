import { ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";

export default function useFlashMessage() {

    const message = ref(null);

    router.on("finish", () => {
        message.value = usePage().props.flash.message;
        setTimeout(() => {
            message.value = null;
        }, 5000);
    });

    return { message };
}