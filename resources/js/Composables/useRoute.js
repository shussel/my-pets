import { ref, computed, reactive } from "vue";

// global singleton
const pageRoute = reactive({
    available: ref({}),
    current: ref({}),
    base: computed(() => pageRoute.current.name.substring(pageRoute.current.name.indexOf(".") + 1)),
    view: computed(() => pageRoute.available[pageRoute.base])
});

export default function useRoute(setRoute = {}, views = {}) {

    // set available views
    if (Object.keys(views).length) {
        pageRoute.available = views;
    }

    // init or change route
    if (Object.keys(setRoute).length) {
        if ((JSON.stringify(setRoute) !== JSON.stringify(pageRoute.current)) && Object.keys(pageRoute.current).length) {
            history.pushState(null, null, route(setRoute.name, setRoute.params));
        }
        pageRoute.current = setRoute;
    }

    return { pageRoute };
}