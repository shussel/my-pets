<script setup>
import {library} from "@fortawesome/fontawesome-svg-core";
import {
    faBaseball,
    faBone,
    faBowlRice,
    faCarSide,
    faCat,
    faCircleHalfStroke,
    faCircleUser,
    faCrow,
    faDog,
    faDragon,
    faFileLines,
    faFishFins,
    faGear,
    faHorse,
    faKitMedical,
    faMoon,
    faPaw,
    faPoop,
    faRectangleXmark,
    faSun,
    faTree,
    faXmark,
} from "@fortawesome/free-solid-svg-icons";

import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

library.add(
    faBaseball,
    faBone,
    faBowlRice,
    faCarSide,
    faCat,
    faCircleHalfStroke,
    faCircleUser,
    faCrow,
    faDog,
    faDragon,
    faFileLines,
    faFishFins,
    faGear,
    faHorse,
    faKitMedical,
    faMoon,
    faPaw,
    faPoop,
    faRectangleXmark,
    faSun,
    faTree,
    faXmark,
);

const props = defineProps({
    name: {
        type: String,
        required: true
    },
});

const namedIcons = {
    auto: "fa-circle-half-stroke",
    bird: "fa-crow",
    bone: "fa-bone",
    car: "fa-car-side",
    cat: "fa-cat",
    dark: "fa-moon",
    delete: "fa-xmark",
    document: "fa-file-lines",
    dog: "fa-dog",
    fish: "fa-fish-fins",
    food: "fa-bowl-rice",
    gear: "fa-gear",
    help: "fa-kit-medical",
    horse: "fa-horse",
    light: "fa-sun",
    paw: "fa-paw",
    play: "fa-baseball",
    poop: "fa-poop",
    reptile: "fa-dragon",
    tree: "fa-tree",
    user: "fa-circle-user",
};
</script>

<template>
    <font-awesome-icon :icon="namedIcons[name]"/>
</template>
