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
                        toggle: [{ action: 'Let In', state: 'Inside'},{ action: 'Let Out', state: 'Outside'}],
                        location: 'Inside',
                        startTime: '07:00',
                        endTime: '19:00',
                    },
                    crate: {
                        action: 'Crate',
                        toggle: [{ action: 'Put in Crate', state: 'Crated'},{ action: 'Exit Crate', state: 'Free'}],
                        startTime: '19:00',
                        endTime: '07:00',
                    },
                    kennel: {
                        action: 'Put in',
                        toggle: [{ action: 'Put in Kennel', state: 'Kennelled'},{ action: 'Exit Kennel', state: 'Free'}],
                        with: 'Kennel',
                        startTime: '07:00',
                        endTime: '19:00',
                    },
                    'dog-house': {
                        action: 'Let out',
                        toggle: [{ action: 'Let Out', state: 'Outside'},{ action: 'Let In', state: 'Inside'}],
                        location: 'Inside',
                        startTime: '07:00',
                        endTime: '19:00',
                    },
                    outside: {
                        action: 'Let out',
                        toggle: [{ action: 'Let Out', state: 'Outside'},{ action: 'Let In', state: 'Inside'}],
                        location: 'Outside',
                        startTime: '07:00',
                        endTime: '19:00',
                    },
                    cage: {
                        action: 'Cover',
                        toggle: [{ action: 'Cover On At', state: 'Covered'},{ action: 'Cover Off At', state: 'Uncovered'}],
                        with: 'Cage',
                        startTime: '19:00',
                        endTime: '07:00',
                    },
                    tank: {
                        action: 'Light off',
                        toggle: [{ action: 'Light Off At', state: 'Light off'},{ action: 'Light On At', state: 'Light on'}],
                        location: 'Tank',
                        startTime: '19:00',
                        endTime: '07:00',
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
                        toggle: [{ action: 'Let In', state: 'In Stable'},{ action: 'Let Out', state: 'Outside'}],
                        with: 'Stable',
                        startTime: '19:00',
                        endTime: '07:00',
                    },
                    pasture: {
                        action: 'Let out',
                        toggle: [{ action: 'Let Out', state: 'Outside'},{ action: 'Let In', state: 'Inside'}],
                        location: 'Pasture',
                        startTime: '19:00',
                        endTime: '07:00',
                    },
                }
            }
        }"
            :pet="pet"
            category="Sleep"
    />
</template>

