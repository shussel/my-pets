/* TIME RULES
    - time is always returned from the server as ISO8601 string: 2024-02-05T20:44:44+00:00|Z
    - rely on js client values for user's timezone offset: +05:00
    - subtract tz offset, reformat as ISO8601, trim to 16 char for datetime-local input: 2024-02-05T15:44
    - before sending back to the server, seconds and offset must be added back: 2024-02-05T15:44:00+05:00
    - server will save as UTC: 2024-02-05T20:44:44Z
 */

const intervals = {
    minute: 1,
    hour: 60,
    day: 1440,
    week: 10080,
    month: 302400,
    year: 3628800
};

const repeats = {
    "x per": "times-per",
    every: "every",
    once: "once"
};

const utcDatePattern = /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}[\s\S]+$/;

// convert date UTC for datetime-local input
function utcToLocal(utcDate) {
    if (utcDatePattern.test(utcDate) ^ !utcDate) {
        const localDate = new Date(utcDate || Date.now());
        localDate.setMinutes(localDate.getMinutes() - localDate.getTimezoneOffset());
        return localDate.toISOString().slice(0, 16);
    }
    return null;
}

// convert date properties in object to datetime-local
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

const localDatePattern = /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/;

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
        let totalMinutes = (60 * parseInt(startHour)) + parseInt(startMinute) + (60 * parseInt(hours)) + parseInt(minutes);
        totalMinutes = totalMinutes < 1440 ? totalMinutes : 0;
        clockHour = parseInt(totalMinutes / 60);
        clockMinute = parseInt(totalMinutes % 60);
    }
    return clockHour.toString().padStart(2, 0) + ":" + clockMinute.toString().padStart(2, 0);
}

function clock12hours(clockTime) {
    let [hours = "00", minutes = "00"] = clockTime.split(":");
    return (!(hours = parseInt(hours))) ? "midnight" :
        (hours > 12) ? (hours - 12).toString() + ":" + minutes + "PM" :
            (hours === 12) ? "noon" :
                String(hours) + ":" + minutes + "AM";
}

// get list of daily times at interval between start and end
function dailyIntervalHours(between, count, interval = 60) {

    const timeList = [];
    let start, end, time;
    [start = "00:00", end = "00:00"] = between.sort();
    time = start;

    // 1 interval minimum except minutes min 15
    count = interval === 1 && count < 15 ? 15 : (count < 1 ? 1 : count);
    // 90 interval maximum except hours 8
    count = interval === 60 && count > 8 ? 8 : (count > 90 ? 90 : count);

    // add interval counts from start until end or clock returns to 0
    while (time && (!timeList.length || ((time <= end || end === "00:00") && time !== "00:00"))) {
        timeList.push(time);
        time = interval === 60 ? getClockTime(count, 0, time) :
            (interval === 1 ? getClockTime(0, count, time) : null);
    }
    return timeList;
}

// last time in a list of times which has elapsed today, or yesterday if none have elapsed
function lastTimeElapsed(between, count, interval, times = []) {
    const timeList = times.length ? times : ((count ? dailyIntervalHours(between, count, interval) : between?.sort()) || []).filter(Boolean);
    console.log("timeList", timeList);
    let lastHours, yesterday = 0;
    const hoursNow = getClockTime();
    for (const time of timeList) {
        if (hoursNow > time) {
            lastHours = time;
        } else if (!lastHours || yesterday) {
            lastHours = time;
            yesterday = 1;
        }
    }
    // get today or yesterday
    const lastDate = utcToLocal(
        (d => new Date(d.setDate(d.getDate() - yesterday)))(new Date).toISOString()
    );
    return lastHours ? lastDate.substring(0, 11) + lastHours : lastDate;
}

function nextTime(between, count, interval, times = []) {
    const timeList = times.length ? times : ((count ? dailyIntervalHours(between, count, interval) : between?.sort()) || []).filter(Boolean);
    console.log("timeList", timeList);
    let nextHours, tomorrow = 0;
    const hoursNow = getClockTime();
    for (const time of timeList) {
        if (hoursNow < time && !nextHours) {
            nextHours = time;
        }
    }
    if (!nextHours) {
        nextHours = timeList[0];
        tomorrow = 1;
    }
    // get today or tomorrow
    const nextDate = utcToLocal(
        (d => new Date(d.setDate(d.getDate() + tomorrow)))(new Date).toISOString()
    );
    return nextHours ? nextDate.substring(0, 11) + nextHours : nextDate;
}

function getDailyTimes(count) {
    count = parseInt(count);
    return count === 1 ? ["06:00"] :
        (count === 2 ? ["06:00", "18:00"] :
            (count === 3 ? ["06:00", "12:00", "18:00"] :
                (count === 4 ? ["06:00", "10:00", "14:00", "18:00"] : [])));
}

export default {
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
    nextTime
};