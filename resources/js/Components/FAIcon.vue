<script setup>
import {library} from "@fortawesome/fontawesome-svg-core";
import {
    faBaseball,
    faBone,
    faBowlRice,
    faCarSide,
    faCat,
    faCircleUser,
    faCrow,
    faDog,
    faDragon,
    faFileLines,
    faFishFins,
    faGear,
    faHorse,
    faKitMedical,
    faPaw,
    faPoop,
    faRectangleXmark,
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
    faCircleUser,
    faCrow,
    faDog,
    faDragon,
    faFileLines,
    faFishFins,
    faGear,
    faHorse,
    faKitMedical,
    faPaw,
    faPoop,
    faRectangleXmark,
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
    bird: "fa-crow",
    bone: "fa-bone",
    car: "fa-car-side",
    cat: "fa-cat",
    delete: "fa-xmark",
    document: "fa-file-lines",
    dog: "fa-dog",
    fish: "fa-fish-fins",
    food: "fa-bowl-rice",
    gear: "fa-gear",
    help: "fa-kit-medical",
    horse: "fa-horse",
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
