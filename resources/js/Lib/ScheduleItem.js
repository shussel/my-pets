export default class ScheduleItem {

    constructor(item = {}) {
        console.log("scheduleItem.data", item);
        // update class properties which exist in input object, shallow copy array and object properties
        for (const key of Object.keys(this)) {
            if (key in item) {
                this[key] = Array.isArray(item[key]) ? [...item[key]] : ((item[key] instanceof Object) ? { ...item[key] } : item[key]);
            }
        }
        console.log("scheduleItem.construct", this);
    }
}
