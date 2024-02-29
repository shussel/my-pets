<script setup>
import { computed, toRefs, ref, watchEffect, watch } from "vue";
import dt from "@/Lib/datetime.js";
import InputLabel from "@/Components/InputLabel.vue";
import InputText from "@/Components/InputText.vue";
import InputButtons from "@/Components/InputButtons.vue";
import ButtonDefault from "@/Components/ButtonDefault.vue";
import FormFooter from "@/Components/FormFooter.vue";
import FAIcon from "@/Components/FAIcon.vue";
import useScheduleItem from "@/Composables/useScheduleItem.js";

const props = defineProps({
    scheduleItem: {
        type: Object,
        default: () => ({}),
    },
    editState: {
        type: Object,
        default: () => ({}),
    },
    form: {
        type: Object,
        default: () => ({}),
    },
    options: {
        type: Object,
        default: () => ({}),
    },
});

const emit = defineEmits(["update:scheduleItem", "update:editState"]);

const { scheduleItem: propsItem } = toRefs(props);
const { scheduleItem: useItem, repeat, hours, days, times, description, status, isValid } = useScheduleItem(propsItem);

// v-model proxy
const editItem = computed({
    get: () => useItem,
    set: (value) => emit("update:scheduleItem", value),
});

// v-model proxy
const edit = computed({
    get: () => props.editState,
    set: (value) => emit("update:editState", value),
});
const editing = computed(() => edit.value.editing === editItem.value._id);
const managing = computed(() => edit.value.managing === editItem.value._id);

const minCount = computed(() => repeat.every && repeat.minutes ? 15 :
    (repeat.every && repeat.dayOrMore ? 1 : 1));

const maxCount = computed(() => repeat.every ? (repeat.hourly ? 12 : 90) :
    repeat.timesPer ? (repeat.daily ? 4 : 1) : 1);

const useIntervals = computed(() => {
    return {
        3628800: false,
        302400: false,
        10080: false,
        1440: (repeat.repeating && editItem.value.count <= maxCount.value),
        60: (repeat.timesPer && !repeat.multi) ||
            (repeat.every && editItem.value.count <= maxCount.value),
        1: (repeat.every && editItem.value.count >= minCount.value),
    };
});
const selectInterval = computed(() => [...dt.intervals]?.filter(({ value }) => useIntervals.value[value]));

const useRepeats = computed(() => {
    return {
        "times-per": !repeat.toggle,
        every: true,
        once: !repeat.toggle,
    };
});
const selectRepeat = computed(() => dt.repeats?.filter(({ value }) => useRepeats.value[value]));

const statusColor = computed(() => status.value === "active" ? "text-green-600" :
    status.value === "done" ? "text-red-600" : "text-yellow-600"
);

const setHours = ref(false);
const showHours = computed(() => !hours.all || setHours.value);

function toggleHours() {
    if (setHours.value || !hours.all) {
        editItem.value.startTime = editItem.value.endTime = "00:00";
    }
    setHours.value = !setHours.value;
}

const setTimes = ref(false);

function toggleTimes() {
    if (times.chosen) {
        editItem.value.times = [];
        if (repeat.oncePer) {
            editItem.value.repeat = "every";
        }
    } else {
        if (repeat.everyOne) {
            editItem.value.repeat = "times-per";
        } else if (repeat.every) {
            editItem.value.times = dt.getDailyTimes(1);
        } else {
            editItem.value.times = dt.getDailyTimes(editItem.value.count);
        }
    }
    setHours.value = false;
    setTimes.value = !setTimes.value;
}

// update timelist
watch([() => editItem.value.count, () => editItem.value.interval, () => editItem.value.repeat, () => editItem.value.startTime, () => editItem.value.endTime], () => {
    if (repeat.dayOrMore && repeat.timesPer) {
        if (repeat.timesPer && (!hours.all || editItem.value.count !== editItem.value.times.length)) {
            editItem.value.times = !hours.all ? dt.dailyIntervalHours([editItem.value.startTime, editItem.value.endTime], editItem.value.count, 0) :
                dt.getDailyTimes(editItem.value.count);
            console.log("adjusting times count", editItem.value.times);
        }
    } else if (times.chosen) {
        console.log("clearing times");
        editItem.value.times = [];
    }
});

// update count keep min/max values depending on count, interval and repeat
watch([() => editItem.value.count, () => editItem.value.interval, () => editItem.value.repeat], () => {
    if (editItem.value.count < minCount.value) {
        editItem.value.count = minCount.value;
        console.log("enforcing min count");
    } else if (editItem.value.count > maxCount.value) {
        console.log("enforcing max count");
        editItem.value.count = maxCount.value;
    }
    if (repeat.repeating) {
        if (!editItem.value.interval) {
            editItem.value.interval = 1440;
            console.log("set default interval");
        } else if (editItem.value.interval < 60) {
            editItem.value.interval = 60;
            editItem.value.count = 1;
            console.log("enforcing no times per minute");
        }
    }
});

// reset all parameters to non-repeating event
watch(() => editItem.value.repeat, () => {
    if (repeat.none) {
        console.log("clearing to once");
        if (times.chosen) {
            editItem.value.times = [];
        }
        editItem.value.interval = null;
        editItem.value.count = 1;
        editItem.value.startTime = editItem.value.endTime = "00:00";
        editItem.value.last = editItem.value.next = null;
    }
});

// update last date when parameters change
watch([() => editItem.value.count, () => editItem.value.interval, () => editItem.value.repeat, () => editItem.value.times, () => editItem.value.dow, () => editItem.value.startTime, () => editItem.value.endTime], () => {
    // update last
    if (editItem.value.last !== repeat.lastPossible) {
        console.log("update last", editItem.value.last, repeat.lastPossible);
        editItem.value.last = repeat.lastPossible;
    }
});

function updateNext() {
    if (editItem.value.next !== repeat.nextRegular) {
        console.log("update next");
        editItem.value.next = repeat.nextRegular;
    }
}
</script>

<template>
    <div class="space-y-3">

        <div v-if="editItem.repeat"
             class="pt-2 pb-1 text-lg text-center flex flex-wrap justify-center items-center gap-x-2">
            <div v-if="editItem.action">
                {{ repeat.toggle && hours.all ? editItem.toggle[0].state : editItem.action + " " + editItem.with }}
            </div>
            <div v-if="repeat.description">{{ repeat.description }}</div>
            <div v-if="days.description">{{ days.description }}</div>
            <div v-if="hours.description">{{ hours.description }}</div>
            <div v-if="times.description">{{ times.description }}</div>
        </div>

        <template v-if="editing">

            <div v-if="repeat.none" class="flex items-center gap-2">
                <div class="w-1/2">
                    <InputLabel for="action" value="Action"/>
                    <InputText
                            id="action"
                            v-model="editItem.action"
                            autocomplete="off"
                            class="block w-full mt-1"
                            type="text"
                    />
                </div>
                <div class="w-1/2">
                    <InputLabel for="location" value="Location"/>
                    <InputText
                            id="location"
                            v-model="editItem.location"
                            autocomplete="no"
                            class="block w-full mt-1"
                            type="text"
                    />
                </div>
            </div>

            <!-- repeat params -->
            <template v-if="repeat.repeating || !repeat.isSet">
                <div class="flex justify-center items-center gap-2">
                    <div>{{ [editItem.action, editItem.with].filter(Boolean).join(" ") || "Repeat" }}</div>
                    <InputText
                            v-if="repeat.timesPer"
                            id="count"
                            v-model="editItem.count"
                            :max="maxCount"
                            :min="minCount"
                            autocomplete="off"
                            class="block mt-1"
                            type="number"
                    />
                    <InputButtons
                            id="repeat"
                            :key="editItem.repeat"
                            v-model="editItem.repeat"
                            :options="selectRepeat"
                            class="gap-0"
                    />
                    <InputText
                            v-if="repeat.every"
                            id="count"
                            v-model="editItem.count"
                            :max="maxCount"
                            :min="minCount"
                            autocomplete="off"
                            class="block mt-1"
                            type="number"
                    />
                    <InputButtons
                            id="interval"
                            :key="editItem.interval"
                            v-model="editItem.interval"
                            :options="selectInterval"
                            class="gap-0"
                    />
                </div>
            </template>

            <template v-if="repeat.repeating">

                <div class="flex flex-wrap justify-around items-center gap-x-2 gap-y-3">

                    <!-- daily count -->
                    <div v-if="repeat.lessThanDaily" class="text-center">
                        <Label>{{ repeat.dailyCount }}x</Label>
                    </div>

                    <!-- days of week -->
                    <div class="text-center align-middle">
                        <span class="font-medium mr-1">{{ days.all ? "Every Day" : "On Days" }}</span>
                        <span class="text-sm underline"
                              @click="editItem.dow = (days.all ? [...dt.DAYS] : [])">
                                {{ days.all ? "days" : "every" }}</span>
                        <template v-if="!days.all">
                            | <span class="text-sm underline"
                                    @click="editItem.dow = [...dt.WEEKDAYS]"
                        >weekdays</span>
                            | <span class="text-sm underline"
                                    @click="editItem.dow = [...dt.WEEKEND]"
                        >weekend</span>
                        </template>
                    </div>

                    <div v-if="!days.all" class="w-full shrink-0 text-center font-bold flex gap-2 items-center">
                        <div v-for="(day) of dt.DAYS">
                            <label>
                                <input
                                        :id="'dow' + day"
                                        v-model="editItem.dow"
                                        :value="day"
                                        class="rounded border-slate-700 dark:border-slate-500 text-slate-500 dark:bg-slate-300 shadow-sm focus:ring-slate-400"
                                        type="checkbox"
                                />
                                {{ day }}
                            </label>
                        </div>
                    </div>

                    <!-- hours of the day -->
                    <template v-if="!times.chosen">
                        <div class="text-center align-middle">
                            <span class="font-medium mr-1">{{ showHours ? "Between" : "All Day" }}</span>
                            <span class="text-sm underline" @click="toggleHours">
                                {{ showHours ? "all day" : "hours" }}
                            </span>
                        </div>

                        <div v-if="showHours" class="w-full flex items-center gap-2">
                            <div class="grow min-w-1/2">
                                <InputLabel for="startTime" :value="hours.startTitle"/>
                                <InputText
                                        id="startTime"
                                        v-model="editItem.startTime"
                                        autocomplete="off"
                                        class="block w-full mt-1"
                                        type="time"
                                />
                            </div>
                            <div class="grow min-w-1/2">
                                <InputLabel for="endTime" :value="hours.endTitle"/>
                                <InputText
                                        id="endTime"
                                        v-model="editItem.endTime"
                                        autocomplete="off"
                                        class="block w-full mt-1"
                                        type="time"
                                />
                            </div>
                        </div>
                    </template>

                    <!-- time list -->
                    <template v-if="editItem.interval >= 1440 && !repeat.toggle">
                        <div class="text-center align-middle">
                            <span class="font-medium mr-1">{{ !times.chosen ? "Any Time" : "Time" + (repeat.timesPer && times.multi ? "s" : "")
                                }}</span>
                            <span class="text-sm underline" @click="toggleTimes">
                                {{ !times.chosen ? "time" + (repeat.timesPer && times.multi ? "s" : "") : "any" }}
                            </span>
                        </div>

                        <div v-if="setTimes || times.chosen"
                             class="w-full flex flex-wrap justify-center items-center gap-2">
                            <div v-for="(time,index) in editItem.times" class="max-w-1/2">
                                <InputText
                                        :id="'time' + index + 1"
                                        v-model="editItem.times[index]"
                                        autocomplete="off"
                                        class="block w-full mt-1"
                                        type="time"
                                />
                            </div>
                        </div>
                    </template>

                </div>

                <!-- manage -->
                <div class="flex justify-between items-center gap-4">
                    <div class="grow flex justify-around items-center gap-3">
                        <div :class="'font-bold ' + statusColor ">{{ status }}</div>
                        <div v-if="status === 'done' || status === 'paused'" class="text-center">
                            <span class="font-bold">last</span> <span class="whitespace-nowrap">{{ repeat.lastCalendar
                            }}</span>
                        </div>
                        <div v-if="status === 'active'" class="text-center">
                            <span class="font-bold">next</span> <span class="whitespace-nowrap">{{ repeat.nextCalendar
                            }}</span>
                        </div>
                    </div>
                    <ButtonDefault class="w-12"
                                   @click.prevent="!managing ? edit.managing=editItem._id : edit.managing=null">
                        <FAIcon class="text-xl" name="sliders"/>
                    </ButtonDefault>
                </div>

                <template v-if="managing">
                    <div>
                        <div class="mb-1 flex justify-between">
                            <InputLabel for="last" value="Last"/>
                            <div v-if="editItem.last" class="text-sm">{{ dt.untilAgo(editItem.last) }}</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <InputText
                                    id="last"
                                    v-model="editItem.last"
                                    autocomplete="off"
                                    class="w-full"
                                    type="datetime-local"
                                    :disabled="status !== 'active'"
                            />
                            <ButtonDefault :disabled="status !== 'active' || editItem.last >= repeat.lastPossible"
                                           class="w-14"
                                           @click.prevent="editItem.last = repeat.lastPossible">
                                <FAIcon class="text-xl" name="fastForward"/>
                            </ButtonDefault>
                        </div>
                    </div>

                    <div>
                        <div class="mb-1 flex justify-between">
                            <InputLabel for="next" value="Next"/>
                            <div v-if="repeat.started && (editItem.next || repeat.nextRegular) && status !== 'done'"
                                 class="text-sm">{{ dt.untilAgo(editItem.next || repeat.nextRegular) }}
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            {{ editItem.next }}
                            <InputText
                                    v-if="!editItem.next && status === 'active'"
                                    id="next"
                                    v-model="repeat.nextRegular"
                                    :disabled="false"
                                    autocomplete="off"
                                    class="w-full"
                                    type="datetime-local"
                            />
                            <InputText
                                    v-else
                                    id="next"
                                    v-model="editItem.next"
                                    autocomplete="off"
                                    class="w-full"
                                    type="datetime-local"
                                    :disabled="true"
                            />
                            <ButtonDefault :disabled="status !== 'active'" class="w-14"
                                           @click.prevent="editItem.next = null">
                                <FAIcon class="text-xl" name="stepForward"/>
                            </ButtonDefault>
                        </div>
                    </div>
                </template>
            </template>

            <template v-if="repeat.none || (managing && repeat.isSet)">

                <div>
                    <div class="mb-1 flex justify-between">
                        <InputLabel :value="!repeat.repeating ? 'Date' : 'Start Date'" for="startDate"/>
                        <div v-if="editItem.startDate" class="text-sm">{{ dt.untilAgo(editItem.startDate) }}</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <InputText
                                id="startDate"
                                v-model="editItem.startDate"
                                :disabled="status === 'done'"
                                autocomplete="off"
                                class="w-full"
                                type="datetime-local"
                        />
                        <ButtonDefault v-if="!repeat.repeating" :disabled="status === 'done'"
                                       class="w-14" @click.prevent="editItem.repeat = null">
                            repeat
                        </ButtonDefault>
                        <ButtonDefault v-else-if="editItem.startDate" :disabled="status === 'done'"
                                       class="w-14" @click.prevent="editItem.startDate = null">
                            <FAIcon class="text-xl" name="pause"/>
                        </ButtonDefault>
                        <ButtonDefault v-else :disabled="status === 'done'" class="w-14"
                                       @click.prevent="editItem.startDate = dt.utcToLocal()">
                            <FAIcon class="text-xl" name="play"/>
                        </ButtonDefault>
                    </div>
                </div>

                <div>
                    <div class="mb-1 flex justify-between">
                        <InputLabel for="endDate" value="End Date"/>
                        <div v-if="editItem.endDate" class="text-sm">{{ dt.untilAgo(editItem.endDate) }}</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <InputText
                                id="endDate"
                                v-model="editItem.endDate"
                                autocomplete="off"
                                class="w-full"
                                type="datetime-local"
                        />
                        <ButtonDefault v-if="editItem.endDate" class="w-14" @click.prevent="editItem.endDate = null">
                            <FAIcon class="text-xl" name="play"/>
                        </ButtonDefault>
                        <ButtonDefault v-else class="w-14" @click.prevent="editItem.endDate = dt.utcToLocal()">
                            <FAIcon class="text-xl" name="stop"/>
                        </ButtonDefault>
                    </div>
                </div>
            </template>
        </template>

        <FormFooter v-if="editing" :form="form" :isValid="isValid" :message="edit.message"/>
    </div>
</template>