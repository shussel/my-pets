import { reactive, toValue, isReactive, computed } from "vue";
import ObjectID from "bson-objectid";
import dt from "@/Lib/datetime.js";

const defaultScheduleItem = {
    _id: null,
    category: null,
    action: null,
    with: null,
    toggle: [],
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

export default function useScheduleItem(itemData) {

    // don't make item data reactive twice
    const scheduleItem = isReactive(toValue(itemData)) ? toValue(itemData) : reactive({
        ...defaultScheduleItem,
        ...(typeof toValue(itemData) === "object" && toValue(itemData) !== null ? toValue(itemData) : {}),
    });

    const repeat = reactive({
        isSet: computed(() => scheduleItem.repeat),
        none: computed(() => repeat.isSet && scheduleItem.repeat === "once"),
        repeating: computed(() => repeat.isSet && scheduleItem.repeat !== "once"),
        once: computed(() => repeat.repeating && parseInt(scheduleItem.count) === 1),
        multi: computed(() => repeat.repeating && parseInt(scheduleItem.count) > 1),
        toggle: computed(() => scheduleItem.toggle?.length),
        timesPer: computed(() => scheduleItem.repeat === "times-per"),
        oncePer: computed(() => repeat.timesPer && repeat.once),
        every: computed(() => scheduleItem.repeat === "every"),
        everyOne: computed(() => repeat.every && repeat.once),
        minutes: computed(() => parseInt(scheduleItem.interval) === 1),
        hourly: computed(() => parseInt(scheduleItem.interval) === 60),
        lessThanDaily: computed(() => parseInt(scheduleItem.interval) < 1440),
        daily: computed(() => parseInt(scheduleItem.interval) === 1440),
        dayOrMore: computed(() => parseInt(scheduleItem.interval) >= 1440),
        started: computed(() => scheduleItem.startDate || scheduleItem.startDate <= dt.localToUtc()),
        lastPossible: computed(() =>
            repeat.repeating ? dt.lastTimeElapsed([scheduleItem.startTime, scheduleItem.endTime], scheduleItem.count, scheduleItem.interval, scheduleItem.times, scheduleItem.repeat, scheduleItem.dow) : null
        ),
        lastTime: computed(() => dt.shortTime(scheduleItem.last || repeat.lastPossible)),
        lastDay: computed(() => dt.shortDate(scheduleItem.last || repeat.lastPossible)),
        lastCalendar: computed(() => dt.statusDate(scheduleItem.last || repeat.lastPossible)),
        nextRegular: computed(() =>
            repeat.repeating ? dt.nextTime([scheduleItem.startTime, scheduleItem.endTime], scheduleItem.count, scheduleItem.interval, scheduleItem.times, scheduleItem.repeat, scheduleItem.dow, scheduleItem.last) : null
        ),
        nextTime: computed(() => dt.shortTime(scheduleItem.next || repeat.nextRegular)),
        nextDay: computed(() => dt.shortDate(scheduleItem.next || repeat.nextRegular)),
        nextCalendar: computed(() => dt.statusDate(scheduleItem.next || repeat.nextRegular)),
        intervalName: computed(() =>
            !scheduleItem.interval ? "" :
                dt.intervals.find(({ value }) => value === scheduleItem.interval).label + (repeat.every && repeat.multi ? "s" : "")
        ),
        dailyCount: computed(() => !repeat.repeating ? 0 :
            // times specified
            times.chosen ? scheduleItem.times.length :
                // once per day
                repeat.dayOrMore && (repeat.once || repeat.every) ? 1 :
                    // every less than a day
                    repeat.every ? dt.dailyIntervalHours([scheduleItem.startTime, scheduleItem.endTime], scheduleItem.count, scheduleItem.interval).length :
                        // multiple times per day or more
                        repeat.dayOrMore ? scheduleItem.count :
                            // multi times per interval less than a day
                            dt.dailyIntervalHours([scheduleItem.startTime, scheduleItem.endTime], 1 / scheduleItem.count, scheduleItem.interval).length
        ),
        description: computed(() =>
            !repeat.repeating ? "" :
                (repeat.every ? "every " : "") +
                (repeat.repeating ? (repeat.everyOne ? "" : scheduleItem.count + " ") : "") +
                (repeat.timesPer ? "time" + (repeat.multi ? "s" : "") + " per " : "") +
                (!(repeat.every && repeat.multi && !days.all && repeat.dayOrMore) ? repeat.intervalName || "" : "")
        ),
    });

    // new item
    if (!scheduleItem._id) {
        scheduleItem.new = true;
        scheduleItem._id = ObjectID().toHexString();
        scheduleItem.startDate = scheduleItem.last = repeat.lastPossible;
    }

    const days = reactive({
        count: computed(() => scheduleItem.dow?.length),
        all: computed(() => !days.count),
        setting: computed(() => days.count === 7),
        chosen: computed(() => days.count && !days.setting),
        isWeekdays: computed(() => days.count === 5 && (scheduleItem.dow.filter((day) => dt.WEEKDAYS.includes(day))).length === 5),
        isWeekend: computed(() => days.count === 2 && (scheduleItem.dow.filter((day) => dt.WEEKEND.includes(day))).length === 2),
        on: computed(() => days.isWeekdays ? "weekdays" : days.isWeekend ? "weekends" :
            (days.count === 2 ? scheduleItem.dow.join(" & ") : scheduleItem.dow.join(", "))),
        description: computed(() => days.chosen ?
            (!(repeat.every && repeat.multi && !days.all && repeat.dayOrMore) ? "on " : "") +
            days.on : ""),
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
                    "between " + dt.clock12hours(scheduleItem.startTime) + " & " + dt.clock12hours(scheduleItem.endTime)
        ),
        startTitle: computed(() => repeat.toggle ? scheduleItem.toggle[0] : "Start Time"),
        endTitle: computed(() => repeat.toggle ? scheduleItem.toggle[1] : "End Time"),
        description: computed(() =>
            !hours.all && !times.chosen ? hours.period : ""
        )
    });

    const times = reactive({
        chosen: computed(() => scheduleItem.times.length),
        multi: computed(() => times.chosen > 1),
        description: computed(() => times.chosen ?
            (!times.multi ? "at " : "") + scheduleItem.times.map(time =>
                dt.clock12hours(time)
            ).join(times.chosen === 2 ? " & " : ", ") : "")
    });

    const status = computed(() =>
        scheduleItem.endDate && scheduleItem.endDate <= dt.utcToLocal() ? "done" :
            scheduleItem.startDate && scheduleItem.startDate <= dt.utcToLocal() ? "active" :
                scheduleItem.startDate > dt.utcToLocal() || (!scheduleItem.startDate && !scheduleItem.last) ? "pending" : "paused"
    );

    const isValid = computed(() =>
        // repeating schedule
        (repeat.repeating && scheduleItem.count && scheduleItem.interval) ||
        // one time scheduled event
        (repeat.none && scheduleItem.action))

    const description = computed(() =>
        isValid ?
            [scheduleItem.action, repeat.description, days.description, hours.description, times.description].join(" ") : ""
    );

    return { scheduleItem, repeat, hours, days, times, description, status, isValid };
}
