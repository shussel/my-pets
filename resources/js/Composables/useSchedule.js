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

    function deleteItem(deleteId) {
        const index = schedule.findIndex(({ _id }) => _id === deleteId);
        if (index > -1) schedule.splice(index, 1);
    }

    return { schedule, items, newItem, deleteItem };
}