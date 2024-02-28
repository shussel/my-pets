/* TIME RULES
    - time is always returned from the server as ISO8601 string: 2024-02-05T20:44:44+00:00|Z
    - rely on js client values for user's timezone offset: +05:00
    - subtract tz offset, reformat as ISO8601, trim to 16 char for datetime-local input: 2024-02-05T15:44
    - before sending back to the server, seconds and offset must be added back: 2024-02-05T15:44:00+05:00
    - server will save as UTC: 2024-02-05T20:44:44Z
 */
import dayjs from "dayjs";
import RelativeTime from "dayjs/plugin/relativeTime";
dayjs.extend(RelativeTime);
import Calendar from "dayjs/plugin/calendar";
dayjs.extend(Calendar);

const DAYS = ["Su", "M", "Tu", "W", "Th", "F", "Sa"];
const DAYS_MED = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
const DAYS_FULL = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
const WEEKDAYS = ["M", "Tu", "W", "Th", "F"];
const WEEKEND = ["Su", "Sa"];

const intervals = [
    { label: "minute", value: 1 },
    { label: "hour", value: 60 },
    { label: "day", value: 1440 },
    { label: "week", value: 10080 },
    { label: "month", value: 302400 },
    { label: "year", value: 3628800 },
];

const repeats = [
    { label: "x per", value: "times-per" },
    { label: "every", value: "every" },
    { label: "once", value: "once" },
];

const utcDatePattern = /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}[\s\S]+$/;
const localDatePattern = /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/;

// get or convert date UTC for datetime-local input
function utcToLocal(utcDate) {
    if (utcDatePattern.test(utcDate) ^ !utcDate) {
        const localDate = new Date(utcDate || Date.now());
        localDate.setMinutes(localDate.getMinutes() - localDate.getTimezoneOffset());
        return localDate.toISOString().slice(0, 16);
    }
    return null;
}

// convert UTC date properties in object to datetime-local
function objectUtcToLocal(data) {
    if (data instanceof Object) {
        Object.keys(data).forEach(field => {
            if (utcDatePattern.test(data[field])) {
                data[field] = utcToLocal(data[field]);
            }
        });
    }
    return data;
}

// add user time offset to datetime-local values
function localToUtc(datetimeLocal) {
    if (localDatePattern.test(datetimeLocal)) {
        return datetimeLocal + ":00" + getTzOffset();
    } else if (!datetimeLocal) {
        return (new Date()).toISOString();
    }
    return null;
}

// convert date properties in object to datetime-local
function objectLocalToUtc(data) {
    if (data instanceof Object) {
        Object.keys(data).forEach(field => {
            if (localDatePattern.test(data[field])) {
                data[field] = localToUtc(data[field]);
            }
        });
    }
    return data;
}

function getTzOffset() {
    const tzOffset = new Date().getTimezoneOffset();
    return (tzOffset >= 0 ? "-" : "+") + getClockTime((tzOffset / 60), (tzOffset % 60));
}

function addDays(addDays, addDate) {
    const now = addDate ? new Date(addDate) : new Date();
    return new Date(now.setDate(now.getDate() + parseInt(addDays)));
}

// get hours/minutes as ##:##, defaulting to current time, as optional start time offset
function getClockTime(hours = 0, minutes = 0, startTime = "00:00") {
    let clockHour, clockMinute;
    if (!arguments.length) {
        const now = new Date();
        clockHour = now.getHours();
        clockMinute = now.getMinutes();
    } else {
        startTime = startTime || "00:00";
        const [startHour = "00", startMinute = "00"] = startTime?.split(":");
        let totalMinutes = (60 * parseInt(startHour)) + parseInt(startMinute) + (60 * Number(hours)) + Number(minutes);
        totalMinutes = totalMinutes < 1440 ? totalMinutes : 0;
        clockHour = parseInt(totalMinutes / 60);
        clockMinute = parseInt(totalMinutes % 60);
    }
    return clockHour.toString().padStart(2, 0) + ":" + clockMinute.toString().padStart(2, 0);
}

function clock12hours(clockTime) {
    let [hours = "00", minutes = "00"] = clockTime.split(":");
    return (!(hours = parseInt(hours)) && minutes === "00") ? "midnight" :
        (hours === 12 && minutes === "00") ? "noon" :
            (hours >= 12) ? (hours - 12 || 12).toString() + ":" + minutes + "pm" :
                String(hours || 12) + ":" + minutes + "am";
}

function clockMinutes(clockTime) {
    const [hours = "00", minutes = "00"] = clockTime.split(":");
    return parseInt(minutes) + parseInt(hours) * 60;
}

function shortTime(datetimeLocal) {
    if (!localDatePattern.test(datetimeLocal)) {
        return null;
    }
    const [hours, minutes] = clock12hours(datetimeLocal.slice(11, 16)).split(":");
    return !minutes ? (hours === "midnight" ? "" : hours) :
        (hours + (parseInt(minutes.slice(0, 2)) ? ":" + minutes : minutes.slice(-2))).toLowerCase();
}

function shortDate(datetimeLocal) {
    if (!localDatePattern.test(datetimeLocal)) {
        return null;
    }
    const today = new Date(utcToLocal().slice(0, 10) + "T00:00" + getTzOffset()),
        checkDay = new Date(datetimeLocal.slice(0, 10) + "T00:00" + getTzOffset());
    const daysDiff = (checkDay - today) / (1000 * 60 * 60 * 24);
    return !daysDiff ? "today" : daysDiff === 1 ? "tomorrow" : daysDiff === -1 ? "yesterday" :
        Math.abs(daysDiff) < 8 ? DAYS_FULL[checkDay.getDay()] :
            checkDay.toLocaleDateString(undefined, { month: "short", day: "numeric" });
}

function untilAgo(date) {
    return dayjs(localToUtc(date)).fromNow();
}

function statusDate(dateLocal) {
    return dayjs(localToUtc(dateLocal)).calendar().replace(" at 12:00 AM", "");
}

// get list of daily times at interval between start and end
function dailyIntervalHours(between, count, interval = 60) {

    const timeList = [];
    let start, end, time;
    [start = "00:00", end = "00:00"] = between.sort();
    if (!start) start = "00:00";
    if (!end) end = "00:00";
    time = start;

    if (!interval) {
        interval = parseInt((clockMinutes(end) - clockMinutes(start)) / (count - 1));
    }

    // 1 interval minimum except minutes min 15
    count = interval === 1 && count < 15 ? 15 : (!count ? 1 : count);
    // 90 interval maximum except hours 12
    count = interval === 60 && count > 12 ? 12 : (count > 90 ? 90 : count);

    // add interval counts from start until end or clock returns to 0
    while (time && (!timeList.length || ((time <= end || end === "00:00") && time !== "00:00"))) {
        timeList.push(time);
        time = interval === 60 ? getClockTime(count, 0, time) :
            (interval === 1 ? getClockTime(0, count, time) :
                getClockTime(0, interval, time));
    }
    return timeList;
}

// last time in a list of times which has elapsed today, or daysAgo if none have elapsed
function lastTimeElapsed(between, count, interval, times = [], repeat = "times-per", dow = []) {

    // daily times or 00:00
    const timeList = times.length ? times :
        ((count ? dailyIntervalHours(between, count, interval) : between?.sort()) || []).filter(Boolean);

    // last time
    const nowTime = getClockTime();
    let lastTime, daysAgo = 0;
    for (const time of timeList) {
        if (nowTime > time) {
            lastTime = time;
        } else if (!lastTime || daysAgo) {
            lastTime = time;
            daysAgo = 1;
        }
    }

    daysAgo =
        dow.length ? (d => {
                if (dow.includes(DAYS[d.getDay()])) {
                    // today if one of chosen days
                    return 0;
                } else {
                    // days since last occurance of last chosen day
                    return (d.getDay() - DAYS.indexOf(dow[dow.length - 1]) + 6) % 7 + 1;
                }
            })(new Date) :
            repeat === "every" && parseInt(interval) >= 1440 ? 0 :
                daysAgo;

    if (daysAgo) {
        lastTime = timeList[timeList.length - 1] || "00:00";
    }

    // get date
    const lastDate = utcToLocal(addDays(-daysAgo).toISOString());
    return lastTime ? lastDate.substring(0, 11) + lastTime : lastDate;
}

function nextTime(between, count, interval, times = [], repeat = "times-per", dow = [], last = null) {

    // daily times or 00:00
    const timeList = times.length ? times :
        ((count ? dailyIntervalHours(between, count, interval) : between?.sort()) || []).filter(Boolean);

    // next time
    const nowTime = getClockTime();
    let nextTime, daysUntil = 0;
    for (const time of timeList) {
        if (nowTime < time && !nextTime) {
            nextTime = time;
        }
    }
    if (!nextTime) {
        daysUntil = 1;
    }

    daysUntil =
        dow.length ? (d => (DAYS.indexOf(dow[0]) + 7 - d.getDay()) % 7)(new Date) + (repeat === "every" ? (count - 1) * 7 : 0) :
            repeat === "every" && parseInt(interval) >= 1440 ? (parseInt(count) === 1 ? 1 : daysUntil ? count : 0) :
                daysUntil;

    if (daysUntil) {
        nextTime = timeList[0] || "00:00";
    }

    // get date
    // start from last date
    if (repeat === "every" && parseInt(interval) >= 1440 && parseInt(count) > 1) {

    }

    const nextDate = (repeat === "every" && parseInt(interval) >= 1440 && parseInt(count) > 1) ?
        utcToLocal(addDays(daysUntil, last || lastTimeElapsed(between, count, interval, times, repeat, dow)).toISOString()) :
        utcToLocal(addDays(daysUntil).toISOString());
    return nextTime ? nextDate.substring(0, 11) + nextTime : nextDate;
}

function getDailyTimes(count, between = []) {

    count = parseInt(count);
    return count === 1 ? ["06:00"] :
        (count === 2 ? ["06:00", "18:00"] :
            (count === 3 ? ["06:00", "12:00", "18:00"] :
                (count === 4 ? ["06:00", "10:00", "14:00", "18:00"] : [])));
}

export default {
    DAYS,
    WEEKDAYS,
    WEEKEND,
    intervals,
    repeats,
    utcToLocal,
    localToUtc,
    getClockTime,
    getTzOffset,
    lastTimeElapsed,
    dailyIntervalHours,
    objectUtcToLocal,
    objectLocalToUtc,
    clock12hours,
    getDailyTimes,
    nextTime,
    shortTime,
    shortDate,
    untilAgo,
    statusDate
};