<script setup>
import {library} from '@fortawesome/fontawesome-svg-core';
import {
    faBowlRice,
    faTree,
    faBaseball,
    faCarSide,
    faKitMedical,
    faCircleUser,
    faDog,
    faCat,
    faCrow,
    faFishFins,
    faDragon,
    faPoop
} from '@fortawesome/free-solid-svg-icons';

import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';

library.add(
    faBowlRice,
    faTree,
    faBaseball,
    faCarSide,
    faKitMedical,
    faCircleUser,
    faDog,
    faCat,
    faCrow,
    faFishFins,
    faDragon,
    faPoop
);

const props = defineProps({
    name: {
        type: String,
    },
});

const namedIcons = {
    dog: 'fa-dog',
    cat: 'fa-cat',
    bird: 'fa-crow',
    fish: 'fa-fish-fins',
    reptile: 'fa-dragon',
    food: 'fa-bowl-rice',
    poop: 'fa-poop',
    play: 'fa-baseball',
    car: 'fa-car-side',
    help: 'fa-kit-medical',
    tree: 'fa-tree',
    user: 'fa-circle-user',
}
</script>

<template>
    <font-awesome-icon :icon="namedIcons[name]"/>
</template>
