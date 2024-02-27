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
        if (Object.keys(pageRoute.current).length &&
            (JSON.stringify(setRoute) !== JSON.stringify(pageRoute.current) ||
                route().current() !== setRoute.name)
        ) {
            history.pushState(null, null, route(setRoute.name, setRoute.params));
        }
        console.log("set url");
        pageRoute.current = setRoute;
    }

    return { pageRoute };
}