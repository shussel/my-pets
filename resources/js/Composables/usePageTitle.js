import { ref, toValue, watchEffect } from "vue";

const pageTitle = ref(null);

export default function usePageTitle(title) {

    watchEffect(() => {
        if (toValue(title)) {
            pageTitle.value = toValue(title);
        }
    });

    return { pageTitle };
}