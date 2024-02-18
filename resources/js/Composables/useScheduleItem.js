import { reactive, toValue, isReactive, computed, toRef, watchEffect, watch } from "vue";
import ObjectID from "bson-objectid";
import dt from "@/Lib/datetime.js";

const defaultScheduleItem = {
    _id: null,
    category: null,
    action: null,
    with: null,
    location: null,
    startDate: null,
    repeat: "once",
    count: 1,
    interval: null,
    dow: [],
    startTime: "00:00",
    endTime: "00:00",
    times: [],
    last: null,
    next: null,
    endDate: null,
};

function toSelectList(items) {
    const list = [];
    for (const key in items) {
        list.push({ label: key, value: items[key] });
    }
    return list;
}

export default function useScheduleItem(itemData) {

    // don't make item data reactive twice
    const scheduleItem = isReactive(toValue(itemData)) ? toValue(itemData) : reactive({
        ...defaultScheduleItem,
        ...(typeof toValue(itemData) === "object" && toValue(itemData) !== null ? toValue(itemData) : {}),
    });

    if (!scheduleItem._id) {
        scheduleItem._id = ObjectID().toHexString();
        scheduleItem.startDate = dt.utcToLocal();
    }

    const repeat = reactive({
        title: computed(() => scheduleItem.action || "Repeat"),
        once: computed(() => !scheduleItem.repeat || scheduleItem.repeat === "once"),
        repeating: computed(() => !repeat.once && scheduleItem.interval && scheduleItem.count),
        interval: computed(() => !scheduleItem.interval ? null : Object.keys(dt.intervals).find(key => dt.intervals[key] === scheduleItem.interval) + (repeat.multi ? "s" : "")),
        weekly: computed(() => scheduleItem.repeat === "every" && scheduleItem.interval === 1440),
        multi: computed(() => scheduleItem.count > 1),
        everyMulti: computed(() => scheduleItem.repeat === "every" && repeat.multi),
        timesAny: computed(() => scheduleItem.repeat === "times-per" && scheduleItem.count),
        showCount: computed(() => repeat.everyMulti || repeat.timesAny),
        maxCount: computed(() =>
            scheduleItem.repeat === "every" ? (scheduleItem.interval === 60 ? 12 : 90) :
                scheduleItem.repeat === "times-per" ? (scheduleItem.interval === 1440 ? 4 : 1) : 1
        ),
        minCount: computed(() =>
            scheduleItem.repeat === "every" && scheduleItem.interval === 1 ? 15 : 1
        ),
        last: computed(() =>
            repeat.repeating ? dt.lastTimeElapsed([scheduleItem.startTime, scheduleItem.endTime], scheduleItem.count, scheduleItem.interval, scheduleItem.times) : null
        ),
        next: computed(() =>
            repeat.repeating ? dt.nextTime([scheduleItem.startTime, scheduleItem.endTime], scheduleItem.count, scheduleItem.interval, scheduleItem.times) : null
        ),
        description: computed(() =>
            (scheduleItem.repeat === "every" ? "every " : "") +
            (repeat.showCount ? scheduleItem.count + " " : "") +
            (scheduleItem.repeat === "times-per" ? "time" + (repeat.multi ? "s" : "") + " per " : "") +
            (repeat.weekly && days.chosen ? days.on : repeat.interval)
        ),
        useIntervals: computed(() => {
            return {
                3628800: false,
                302400: false,
                10080: false,
                1440: ((scheduleItem.repeat === "times-per") && (scheduleItem.count <= repeat.maxCount)) ||
                    ((scheduleItem.repeat === "every") && scheduleItem.count <= repeat.maxCount),
                60: ((scheduleItem.repeat === "times-per") && !repeat.multi) ||
                    ((scheduleItem.repeat === "every") && (scheduleItem.count <= repeat.maxCount)),
                1: (scheduleItem.repeat === "every") && (scheduleItem.count >= repeat.minCount),
            };
        }),
        intervals: computed(() => toSelectList(dt.intervals)?.filter((item) => repeat.useIntervals[item.value])),
        useRepeats: computed(() => {
            return {
                "times-per": true,
                every: true,
                once: true,
            };
        }),
        repeats: computed(() => toSelectList(dt.repeats)?.filter((item) => repeat.useRepeats[item.value])),
        dailyCount: computed(() =>
            scheduleItem.interval && scheduleItem.repeat === "every" && scheduleItem.startTime && scheduleItem.endTime ? dt.dailyIntervalHours([scheduleItem.startTime, scheduleItem.endTime], scheduleItem.count, scheduleItem.interval).length : 0
        )
    });

    const days = reactive({
        days: ["M", "Tu", "W", "Th", "F", "Sa", "Su"],
        weekdays: ["M", "Tu", "W", "Th", "F"],
        weekend: ["Sa", "Su"],
        isWeekdays: computed(() => scheduleItem.dow?.length === 5 && (scheduleItem.dow.filter((day) => days.weekdays.includes(day))).length === 5),
        isWeekend: computed(() => scheduleItem.dow?.length === 2 && (scheduleItem.dow.filter((day) => days.weekend.includes(day))).length === 2),
        toDays(useDays) {
            scheduleItem.dow = [...days[useDays]];
            console.log("weekend shit", scheduleItem.dow.map((day) => days.weekend.includes(day)));
        },
        all: computed(() => !scheduleItem.dow?.length),
        title: computed(() => days.all ? "Every Day" : "On Days"),
        action: computed(() => days.all ? "days" : "every day"),
        toggle() {
            scheduleItem.dow = (days.all ? [...days.days] : []);
        },
        setting: computed(() => scheduleItem.dow?.length === 7),
        multi: computed(() => scheduleItem.dow?.length > 1),
        chosen: computed(() => days.multi && !days.setting),
        on: computed(() => days.isWeekdays ? "weekdays" : days.isWeekend ? "weekends" :
            (scheduleItem.dow?.length === 2 ? scheduleItem.dow.join(" and ") : scheduleItem.dow.join(", "))),
        description: computed(() => days.chosen && !repeat.weekly ? "on " + days.on : ""),
    });

    const hours = reactive({
        first: computed(() => scheduleItem.startTime === "00:00"),
        last: computed(() => scheduleItem.endTime === "00:00"),
        all: computed(() => hours.first && hours.last),
        between: computed(() => !hours.first && !hours.last),
        before: computed(() => hours.first && !hours.last),
        after: computed(() => !hours.first && hours.last),
        period: computed(() =>
            hours.before ? "before " + dt.clock12hours(scheduleItem.endTime) :
                hours.after ? "after " + dt.clock12hours(scheduleItem.startTime) :
                    "between " + dt.clock12hours(scheduleItem.startTime) + " and " + dt.clock12hours(scheduleItem.endTime)
        ),
        title: computed(() => hours.all && !hours.set ? "All Day" : "Between"),
        set: toRef(false),
        action: computed(() => hours.all && !hours.set ? "between" : "any time"),
        toggle: () => {
            if (hours.set) {
                scheduleItem.startTime = scheduleItem.endTime = "00:00";
            }
            hours.set = !hours.set;
        },
        description: computed(() =>
            scheduleItem.repeat === "times-per" && scheduleItem.times?.length ?
                "at " + scheduleItem.times.map(time => dt.clock12hours(time)).join(scheduleItem.times?.length === 2 ? " & " : ", ") :
                !hours.all && scheduleItem.repeat !== "times-per" ? hours.period : ""
        )
    });

    const isValid = computed(() =>
            // repeating schedule
            repeat.repeating ||
            // one time scheduled event
            (scheduleItem.action && repeat.once && !repeat.multi && !scheduleItem.interval)) ||
        // task
        (scheduleItem.action && !repeat.multi && !scheduleItem.interval);

    const description = computed(() =>
        isValid ?
            [scheduleItem.action, repeat.description, days.description, hours.description].join(" ") : ""
    );

    function pickTimes() {
        if (repeat.repeating) {
            scheduleItem.times = (scheduleItem.repeat === "every") ? [] : dt.getDailyTimes(scheduleItem.count);
        }
    }

    // adjust times
    watchEffect(() => {
        pickTimes();
    });

    function updateLast() {
        scheduleItem.last = repeat.last;
    }

    // adjust next
    watchEffect(() => {
        updateLast();
    });

    function updateNext() {
        scheduleItem.next = repeat.next;
    }

    // adjust next
    watchEffect(() => {
        updateNext();
    });

    // prevent exceeding count bounds
    watchEffect(() => {
        if (scheduleItem.count > repeat.maxCount) {
            scheduleItem.count = repeat.maxCount;
        } else if (scheduleItem.count < repeat.minCount) {
            scheduleItem.count = repeat.minCount;
        }
    });

    // // swap times out of order
    // watch([() => scheduleItem.time1, () => scheduleItem.time2], () => {
    //     if (scheduleItem.time2 && scheduleItem.time2 < scheduleItem.time1) {
    //         const swap = scheduleItem.time1;
    //         scheduleItem.time1 = scheduleItem.time2;
    //         scheduleItem.time2 = swap;
    //     }
    // });

    return { scheduleItem, repeat, hours, days, description, isValid };
}
