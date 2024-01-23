import { ref, toValue, toRaw, watchEffect } from "vue";

export default function useCurrentData(currentRoute = null, props = []) {

    const currentData = ref(null);

    watchEffect(() => {
        if (toRaw(props.data)) {
            currentData.value = toRaw(props.data).filter(function(item) {
                return item._id === toValue(currentRoute).params;
            })[0] || {};
        }
    });

    return { currentData };
}