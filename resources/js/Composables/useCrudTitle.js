import { ref, toValue, watchEffect } from "vue";

export default function useCrudTitle(routeName, item, singular = "Item", plural = "Items") {

    const title = ref(null);

    watchEffect(() => {
        switch (toValue(routeName)) {
            case "create":
                title.value = "Add " + singular;
                break;
            case "show":
                title.value = toValue(item).name;
                break;
            case "edit":
                title.value = "Edit " + toValue(item).name;
                break;
            case "index":
            default:
                title.value = plural;
        }
    });

    return { title };
}