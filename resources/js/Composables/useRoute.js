import { ref, computed } from "vue";

// global singleton data
const currentRoute = ref({});
const availableViews = ref({});

export default function useRoute(setRoute = {}, views = {}) {

    // set available views
    if (Object.keys(views).length) {
        availableViews.value = views;
    }

    // init or change route
    if (Object.keys(setRoute).length) {
        console.log("change route", setRoute);
        let sameRoute = (JSON.stringify(setRoute) === JSON.stringify(currentRoute.value));
        let first_route = !Object.keys(currentRoute.value).length;
        currentRoute.value = setRoute;
        if (!sameRoute && !first_route) {
            console.log("history route");
            history.pushState(null, null, route(setRoute.name, setRoute.params));
        } else {
            console.log("not saved to history");
        }
    }

    // trim prefix from route name
    const baseRoute = computed(() => {
        return currentRoute.value.name.substring(currentRoute.value.name.indexOf(".") + 1);
    });

    // lookup view for route name
    const currentView = computed(() => {
        return availableViews.value[baseRoute.value];
    });

    return { currentView, baseRoute, currentRoute };
}