import {ref, watchEffect, toValue} from "vue";

export function useRouteView(route_name, id, views) {

    const view = ref(null);

    const updateRoute = () => {
        view.value = views[toValue(route_name).substring(toValue(route_name).indexOf(".") + 1)];
        history.pushState(null, null, route(toValue(route_name), toValue(id)));
    };

    watchEffect(() => {
        updateRoute();
    });

    return {view};
}
