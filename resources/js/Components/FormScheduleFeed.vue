<script setup>
import FormSchedule from "@/Components/FormSchedule.vue";

const props = defineProps({
    schedule: {
        type: Array,
        default: () => ([])
    },
    options: {
        type: Object,
        required: true
    },
});
</script>

<template>
    <FormSchedule
            :options="{
                presets: {
                    title: 'Daily Feedings',
                    list: [
                        { label: 'Once', value: 'once' },
                        { label: 'Twice', value: 'twice' },
                        { label: 'Multi', value: 'multi' },
                        { label: 'Free', value: 'free' }
                    ],
                    default: {
                        action: 'Feed',
                        with: 'Food',
                        location: 'Home',
                        repeat: 'times-per',
                        interval: 1440,
                    },
                    preset: {
                        once: {
                            times: ['07:00'],
                        },
                        twice: {
                            count: 2,
                            times: ['07:00', '19:00'],
                        },
                        multi: {
                            repeat: 'every',
                            count: 3,
                            interval: 60,
                        },
                        free: {
                            action: 'Fill',
                            location: 'Feeder',
                        }
                    }},
            }"
            :schedule="schedule"
            category="Feed"
    />
</template>
