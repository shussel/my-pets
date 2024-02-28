<script setup>
import FormSchedule from "@/Components/FormSchedule.vue";
import { computed } from "vue";

const props = defineProps({
    pet: {
        type: Object,
        required: true,
    },
    options: {
        type: Object,
        required: true,
    },
});

const speciesOptions = computed(() => {
    return (props.options.species).filter(function(specie) {
        return specie.value === props.pet.species;
    })[0]["sleep"];
});
</script>

<template>
    <FormSchedule
            :options="{
            presets: {
                title: 'Sleep',
                list: speciesOptions,
                default: {
                    action: 'Clean',
                    interval: 1440,
                    repeat: 'every',
                 },
                preset: {
                    inside: {
                        action: 'Let in',
                        toggle: ['In','Out'],
                        location: 'Inside',
                        startTime: '07:00',
                        endTime: '19:00',
                    },
                    crate: {
                        action: 'Put in',
                        toggle: ['In','Out'],
                        with: 'Crate',
                        startTime: '07:00',
                        endTime: '19:00',
                    },
                    kennel: {
                        action: 'Put in',
                        toggle: ['In','Out'],
                        with: 'Kennel',
                        startTime: '07:00',
                        endTime: '19:00',
                    },
                    'dog-house': {
                        action: 'Let out',
                        toggle: ['Out','In'],
                        location: 'Dog House',
                        startTime: '07:00',
                        endTime: '19:00',
                    },
                    outside: {
                        action: 'Let out',
                        toggle: ['Out','In'],
                        location: 'Outside',
                        startTime: '07:00',
                        endTime: '19:00',
                    },
                    cage: {
                        action: 'Cover',
                        toggle: ['Cover On','Cover Off'],
                        with: 'Cage',
                        startTime: '07:00',
                        endTime: '19:00',
                    },
                    tank: {
                        action: 'Light off',
                        toggle: ['On','Off'],
                        location: 'Tank',
                        startTime: '07:00',
                        endTime: '19:00',
                    },
                    pond: {
                        with: 'Pond',
                    },
                    aviary: {
                        with: 'Aviary',
                        count: 7,
                    },
                    stable: {
                        action: 'Let in',
                        toggle: ['In','Out'],
                        with: 'Stable',
                        startTime: '07:00',
                        endTime: '19:00',
                    },
                    pasture: {
                        action: 'Let out',
                        toggle: ['Let Out','Let In'],
                        location: 'Pasture',
                        startTime: '07:00',
                        endTime: '19:00',
                    },
                }
            }
        }"
            :pet="pet"
            category="Sleep"
    />
</template>

