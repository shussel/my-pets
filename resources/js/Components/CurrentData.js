import {ref, watchEffect, toValue, toRaw} from "vue";

export function useCurrentData(id, data) {

    const currentData = ref({});

    const updateId = () => {
        currentData.value = toRaw(data).filter(function(item) {
            return item._id === toValue(id);
        })[0];
    };

    watchEffect(() => {
        updateId();
    });

    return {currentData};
}
