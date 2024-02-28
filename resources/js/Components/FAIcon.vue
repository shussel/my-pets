<script setup>
import { ref } from "vue";
import { library } from "@fortawesome/fontawesome-svg-core";
import {
    faAddressCard,
    faBaseball,
    faBone,
    faBowlRice,
    faBrain,
    faCalendar,
    faCarSide,
    faCat,
    faCircle,
    faCircleHalfStroke,
    faCircleUser,
    faClock,
    faCrow,
    faDog,
    faDragon,
    faFileLines,
    faFishFins,
    faForward,
    faForwardFast,
    faForwardStep,
    faGear,
    faHorse,
    faKitMedical,
    faMoon,
    faPause,
    faPaw,
    faPenToSquare,
    faPlay,
    faPoop,
    faRectangleXmark,
    faRepeat,
    faSliders,
    faSquare,
    faStop,
    faSun,
    faTrashCan,
    faTree,
    faXmark,
} from "@fortawesome/free-solid-svg-icons";

import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

library.add(
    faAddressCard,
    faBaseball,
    faBone,
    faBowlRice,
    faBrain,
    faCalendar,
    faCarSide,
    faCat,
    faCircle,
    faCircleHalfStroke,
    faCircleUser,
    faClock,
    faCrow,
    faDog,
    faDragon,
    faFileLines,
    faFishFins,
    faForward,
    faForwardFast,
    faForwardStep,
    faGear,
    faHorse,
    faKitMedical,
    faMoon,
    faPause,
    faPaw,
    faPenToSquare,
    faPlay,
    faPoop,
    faRectangleXmark,
    faRepeat,
    faSliders,
    faSquare,
    faStop,
    faSun,
    faTrashCan,
    faTree,
    faXmark,
);

const props = defineProps({
    name: {
        type: String,
        required: true
    },
    color: {
        type: String,
    },
});

const namedIcons = {
    auto: "fa-circle-half-stroke",
    ball: "fa-baseball",
    bird: "fa-crow",
    bone: "fa-bone",
    brain: "fa-brain",
    calendar: "fa-calendar",
    car: "fa-car-side",
    cat: "fa-cat",
    clock: "fa-clock",
    dark: "fa-moon",
    delete: "fa-xmark",
    document: "fa-file-lines",
    dog: "fa-dog",
    edit: "fa-pen-to-square",
    fastForward: "fa-forward-fast",
    fish: "fa-fish-fins",
    food: "fa-bowl-rice",
    feed: "fa-bowl-rice",
    forward: "fa-forward",
    gear: "fa-gear",
    help: "fa-kit-medical",
    horse: "fa-horse",
    identity: "fa-address-card",
    light: "fa-sun",
    pause: "fa-pause",
    paw: "fa-paw",
    play: "fa-play",
    poop: "fa-poop",
    repeat: "fa-repeat",
    reptile: "fa-dragon",
    sliders: "fa-sliders",
    sleep: "fa-moon",
    stepForward: "fa-forward-step",
    stop: "fa-stop",
    swatch: "fa-circle",
    trash: "fa-trash-can",
    tree: "fa-tree",
    user: "fa-circle-user",
};

const icon = ref(namedIcons[props.name]);
const colorStyle = ref(null);
const colorClass = ref("text-slate-500 dark:text-slate-300");

if (props.color) {
    if (props.color.charAt(0) === "#") {
        colorStyle.value = "color: " + props.color;
        colorClass.value = null;
    } else {
        colorClass.value = props.color;
    }
    // use color swatch if icon does not exist
    if (!icon.value) {
        icon.value = namedIcons["swatch"];
    }
}
</script>

<template>
    <font-awesome-icon v-if="icon" :class="colorClass" :icon="icon" :style="colorStyle"/>
</template>
