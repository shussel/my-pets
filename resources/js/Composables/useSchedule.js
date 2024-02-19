import { ref, toValue, toRaw, reactive, computed } from "vue";
import useScheduleItem from "@/Composables/useScheduleItem.js";

export default function useSchedule(scheduleData, useCategory) {

    const schedule = reactive(Array.isArray(toValue(scheduleData)) ? (toValue(useCategory) ?
        toValue(scheduleData).filter((item) => {
            return item.category === toValue(useCategory);
        }) : toValue(scheduleData)) : []);

    for (const key in schedule) {
        schedule[key] = newItem(schedule[key]);
    }

    const items = reactive({
        has: computed(() => schedule.length > 0),
        count: computed(() => schedule.length),
    });

    function newItem(data) {
        const { scheduleItem } = useScheduleItem({ ...data, category: toValue(useCategory) });
        return scheduleItem;
    }

    return { schedule, items, newItem };
}