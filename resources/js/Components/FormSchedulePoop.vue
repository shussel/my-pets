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
    })[0]["poop"];
});
</script>

<template>
    <FormSchedule
            :options="{
                presets: {
                    title: 'Poop',
                    list: speciesOptions,
                    default: {
                        action: 'Clean',
                        interval: 1440,
                        repeat: 'every',
                     },
                    preset: {
                        walks: {
                            action: 'Walk',
                            times: ['07:00'],
                            repeat: 'times-per',
                        },
                        yard: {
                            action: 'Let out',
                            location: 'Yard',
                            toggle: [{ action: 'Let Out', state: 'Outside'},{ action: 'Let In', state: 'Inside'}],
                            startTime: '07:00',
                            endTime: '19:00',
                        },
                        pasture: {
                            action: 'Let out',
                            location: 'Pasture',
                            toggle: [{ action: 'Let Out', state: 'Outside'},{ action: 'Let In', state: 'Inside'}],
                            startTime: '07:00',
                            endTime: '19:00',
                        },
                        cage: {
                            with: 'Cage',
                            count: 7,
                        },
                        litterbox: {
                            with: 'Litterbox',
                            count: 2,
                        },
                        kennel: {
                            with: 'Kennel',
                            count: 2,
                        },
                        stable: {
                            with: 'Stable',
                            count: 7,
                        },
                        tank: {
                            with: 'Tank',
                            count: 7,
                        },
                        aviary: {
                            with: 'Aviary',
                            count: 7,
                        },
                        pond: {
                            with: 'Pond',
                            count: 30,
                        }
                    }
                }
            }"
            :pet="pet"
            category="Poop"
    />
</template>