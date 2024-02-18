<script setup>
import { computed, watch, toRefs, toRaw } from "vue";
import { useForm } from "@inertiajs/vue3";
import FormScheduleItem from "@/Components/FormScheduleItem.vue";
import FormFooter from "@/Components/FormFooter.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputButtons from "@/Components/InputButtons.vue";
import FAIcon from "@/Components/FAIcon.vue";
import useSchedule from "@/Composables/useSchedule.js";
import usePresets from "@/Composables/usePresets.js";
import usePetAI from "@/Composables/usePetAI.js";
import useRoute from "@/Composables/useRoute.js";
import dt from "@/Lib/datetime.js";

const props = defineProps({
    schedule: {
        type: Array,
        required: true
    },
    category: {
        type: String,
        required: true
    },
    options: {
        type: Object,
        required: true
    }
});

const { schedule, category } = toRefs(props);
const { schedule: formSchedule, items, newItem, deleteItem } = useSchedule(schedule, category);

const form = useForm({
    category: category.value,
    schedule: formSchedule.map(item => dt.objectUtcToLocal(item))
});

watch(() => form.schedule, () => {
    console.log("schedule changed", toRaw(form.schedule));
}, { deep: true });

const { presets: presetProps } = toRefs(props.options);
const { presets, newPreset } = usePresets(presetProps);

if (presets.has) {
    watch(() => presets.new, () => {
        if (presets.new) {
            form.schedule.push(dt.objectUtcToLocal(newItem(newPreset(presets.new))));
        }
    });
}

const isSavable = computed(() => form.isDirty);

const saveSchedule = () => {
    console.log("saving", form.data());
    presets.using = false;
    const { pageRoute } = useRoute();
    form.transform((data) => {
        return {
            category: data.category,
            schedule: data.schedule.map(saveItem => dt.objectLocalToUtc({ ...saveItem }))
        };
    }).patch(route("schedule.update", pageRoute.current.params), {
        preserveScroll: true,
        onSuccess: () => {
            //usePetAI(pet, { name: category });
            useRoute(pageRoute.current);
        },
        onError: () => {
            // clear errors when the form changes
            let unwatch = watch(form, () => {
                form.clearErrors();
                unwatch();
            });
            useRoute(pageRoute.current);
        },
    });
};
</script>

<template>
    <section>
        <h2 class="ml-2">
            <FAIcon :name="category.toLowerCase()" class="text-2xl"/>
            {{ category }}
        </h2>

        <form class="border px-4 pb-1 pt-5 -mt-3 space-y-2 lg:p-6 lg:pb-2" @submit.prevent="saveSchedule">

            <FormScheduleItem v-for="(item, index) in form.schedule"
                              :key="item._id"
                              v-model:schedule-item="form.schedule[index]"
                              :itemCount="items.count"
            >
                <div v-if="items.count > 1" class="font-bold flex justify-between border-b border-slate-700">
                    <div>Schedule {{ 1 + index }}</div>
                    <div @click="deleteItem(item._id)">
                        <FAIcon class="text-base" name="trash"/>
                    </div>
                </div>
            </FormScheduleItem>

            <FormFooter :form="form" :isSavable="isSavable" :message="presets.message"/>

            <div v-show="items.has" class="p3 text-center" @click="presets.add=true">
                Add Another Schedule
            </div>

            <div v-if="presets.show">
                <InputLabel :value="presets.title"/>
                <InputButtons id="presets" v-model="presets.new" :options="presets.list" class="gap-2"/>
            </div>

        </form>
    </section>
</template>
