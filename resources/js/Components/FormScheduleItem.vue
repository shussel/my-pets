<script setup>
import { computed, toRefs, ref } from "vue";
import dt from "@/Lib/datetime.js";
import InputLabel from "@/Components/InputLabel.vue";
import InputText from "@/Components/InputText.vue";
import InputButtons from "@/Components/InputButtons.vue";
import ButtonDefault from "@/Components/ButtonDefault.vue";
import FAIcon from "@/Components/FAIcon.vue";

import useScheduleItem from "@/Composables/useScheduleItem.js";

const props = defineProps({
    scheduleItem: {
        type: Object,
        default: () => ({}),
    },
    options: {
        type: Object,
        default: () => ({}),
    },
});

const emit = defineEmits(["update:scheduleItem"]);

const { scheduleItem: propsItem } = toRefs(props);
const { scheduleItem: useItem, repeat, hours, days, description } = useScheduleItem(propsItem);

// v-model proxy
const editItem = computed({
    get: () => useItem,
    set: (value) => emit("update:scheduleItem", value),
});
</script>

<template>
    <div class="space-y-3">

        <slot/>

        <div v-if="!editItem.editing" class="py-2 text-lg leading-6 flex flex-wrap justify-center items-center gap-1">
            <div>{{ editItem.action }}</div>
            <div>{{ repeat.description }}</div>
            <div>{{ days.description }}</div>
            <div>{{ hours.description }}</div>
            <ButtonDefault @click.prevent="editItem.editing=true">Edit</ButtonDefault>
        </div>

        <template v-else>

            <div v-if="false" class="flex items-center gap-2">
                <div class="w-1/3">
                    <InputLabel for="action" value="Action"/>
                    <InputText
                            id="action"
                            v-model="editItem.action"
                            autocomplete="off"
                            class="block w-full mt-1"
                            type="text"
                    />
                </div>
                <div class="w-1/3">
                    <InputLabel for="with" value="With"/>
                    <InputText
                            id="with"
                            v-model="editItem.with"
                            autocomplete="off"
                            class="block w-full mt-1"
                            type="text"
                    />
                </div>
                <div class="w-1/3">
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

            <template v-if="editItem.repeat !== 'once'">
                <div class="flex justify-center items-center gap-2">
                    <div>{{ repeat.title }}</div>
                    <InputText
                            v-if="editItem.repeat === 'times-per'"
                            id="count"
                            v-model="editItem.count"
                            :max="repeat.maxCount"
                            :min="repeat.minCount"
                            autocomplete="off"
                            class="block mt-1"
                            type="number"
                    />
                    <InputButtons
                            id="repeat"
                            v-model="editItem.repeat"
                            :options="repeat.repeats"
                            class="gap-0"
                    />
                    <InputText
                            v-if="editItem.repeat === 'every'"
                            id="count"
                            v-model="editItem.count"
                            :max="repeat.maxCount"
                            :min="repeat.minCount"
                            autocomplete="off"
                            class="block mt-1"
                            type="number"
                    />
                    <InputButtons
                            id="interval"
                            v-model="editItem.interval"
                            :options="repeat.intervals"
                            class="gap-0"
                    />
                </div>
            </template>

            <template v-if="editItem.repeat && editItem.repeat !== 'once'">

                <div class="flex flex-wrap justify between gap-y-3">

                    <div v-if="repeat.showTimes" class="grow min-w-1/2 text-center">
                        <Label>{{ repeat.dailyCount }}x</Label>
                    </div>

                    <div class="grow min-w-1/2 text-center">
                        <span class="font-medium mr-2">{{ days.title }} </span>
                        <span class="text-sm underline" @click="days.toggle">{{ days.action }}</span>
                        <template v-if="!days.all">
                            | <span class="text-sm underline" @click="days.toDays('weekdays')">weekdays</span>
                            | <span class="text-sm underline" @click="days.toDays('weekend')">weekend</span>
                        </template>
                    </div>

                    <div v-if="!days.all" class="w-full shrink-0 text-center font-bold flex gap-2 items-center">
                        <div v-for="(day) of days.days" class="mr-1">
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

                    <template v-if="!repeat.timesAny">

                        <div class="grow min-w-1/2 text-center">
                            <span class="font-medium mr-2">{{ hours.title }}</span>
                            <span class="text-sm underline" @click="hours.toggle">
                        {{ hours.action }}
                    </span>
                        </div>

                        <div v-if="hours.set || !hours.all" class="w-full flex items-center gap-2">
                            <div class="grow min-w-1/2">
                                <InputLabel for="startTime" value="Start Time"/>
                                <InputText
                                        id="startTime"
                                        v-model="editItem.startTime"
                                        autocomplete="off"
                                        class="block w-full mt-1"
                                        type="time"
                                />
                            </div>
                            <div class="grow min-w-1/2">
                                <InputLabel for="endTime" value="End Time"/>
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

                </div>

                <div v-if="editItem.repeat === 'times-per'">
                    <InputLabel :value="'At Time' + (editItem.times?.length > 1 ? 's' : '')"
                                class="w-full text-center"/>
                    <div class="flex flex-wrap justify-center items-center gap-2">
                        <template v-for="(time,index) in editItem.times">
                            <div class="max-w-1/2">
                                <InputText
                                        :id="'time' + index + 1"
                                        v-model="editItem.times[index]"
                                        autocomplete="off"
                                        class="block w-full mt-1"
                                        type="time"
                                />
                            </div>
                        </template>
                    </div>
                </div>

                <div v-if="!editItem.manage"
                     class="py-2 text-lg leading-6 flex flex-wrap justify-center items-center gap-1">
                    <ButtonDefault @click.prevent="editItem.manage=true">Manage</ButtonDefault>
                </div>

                <template v-else>

                    <div>
                        <div class="mb-1 flex justify-between">
                            <InputLabel for="last" value="Last"/>
                            <div class="text-sm">in # days</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <InputText
                                    id="last"
                                    v-model="editItem.last"
                                    autocomplete="off"
                                    class="w-full"
                                    type="datetime-local"
                            />
                            <ButtonDefault v-if="editItem.last" class="w-14" @click.prevent="editItem.last = null">
                                <FAIcon class="text-xl" name="fastForward"/>
                            </ButtonDefault>
                            <ButtonDefault v-else class="w-14" @click.prevent="editItem.last = dt.utcToLocal()">
                                <FAIcon class="text-xl" name="stepForward"/>
                            </ButtonDefault>
                        </div>
                    </div>

                    <div>
                        <div class="mb-1 flex justify-between">
                            <InputLabel for="next" value="Next"/>
                            <div class="text-sm">in # days</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <InputText
                                    id="next"
                                    v-model="editItem.next"
                                    autocomplete="off"
                                    class="w-full"
                                    type="datetime-local"
                            />
                            <ButtonDefault v-if="editItem.next" class="w-14" @click.prevent="editItem.next = null">
                                <FAIcon class="text-xl" name="stepForward"/>
                            </ButtonDefault>
                            <ButtonDefault v-else class="w-14" @click.prevent="editItem.next = dt.utcToLocal()">
                                <FAIcon class="text-xl" name="fastForward"/>
                            </ButtonDefault>
                        </div>
                    </div>
                </template>
            </template>

            <div v-if="!editItem.repeat || editItem.manage">
                <div class="mb-1 flex justify-between">
                    <InputLabel :value="repeat.once ? 'Date' : 'Start Date'" for="startDate"/>
                    <div class="text-sm">in # days</div>
                </div>
                <div class="flex items-center gap-2">
                    <InputText
                            id="startDate"
                            v-model="editItem.startDate"
                            autocomplete="off"
                            class="w-full"
                            type="datetime-local"
                    />
                    <ButtonDefault v-if="editItem.repeat === 'once'" class="w-14"
                                   @click.prevent="editItem.repeat = null">
                        repeat
                    </ButtonDefault>
                    <ButtonDefault v-else-if="editItem.startDate" class="w-14"
                                   @click.prevent="editItem.startDate = null">
                        <FAIcon class="text-xl" name="pause"/>
                    </ButtonDefault>
                    <ButtonDefault v-else class="w-14" @click.prevent="editItem.startDate = dt.utcToLocal()">
                        <FAIcon class="text-xl" name="play"/>
                    </ButtonDefault>
                </div>
            </div>

            <div v-if="!editItem.repeat || editItem.manage">
                <div class="mb-1 flex justify-between">
                    <InputLabel for="endDate" value="End Date"/>
                    <div class="text-sm">in # days</div>
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
    </div>
</template>