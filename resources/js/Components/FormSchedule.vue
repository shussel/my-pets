<script setup>
import { computed, watch, toRefs, toRaw, reactive, ref, toRef } from "vue";
import { useForm } from "@inertiajs/vue3";
import FormScheduleItem from "@/Components/FormScheduleItem.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputButtons from "@/Components/InputButtons.vue";
import FAIcon from "@/Components/FAIcon.vue";
import ModalConfirm from "@/Components/ModalConfirm.vue";
import useSchedule from "@/Composables/useSchedule.js";
import usePresets from "@/Composables/usePresets.js";
import usePetAI from "@/Composables/usePetAI.js";
import useRoute from "@/Composables/useRoute.js";
import dt from "@/Lib/datetime.js";

const props = defineProps({
    pet: {
        type: Object,
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

const { category } = toRefs(props);
const { schedule } = toRefs(props.pet);
const { schedule: formSchedule, newItem } = useSchedule(schedule, category);

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
            const item = dt.objectUtcToLocal(newItem(newPreset(presets.new)));
            console.log("new preset", item);
            form.schedule.push(item);
            editState.editing = item._id;
        }
    });
}

const editState = reactive({
    editing: ref(false),
    managing: ref(false),
    deleting: ref(false),
    message: computed(() => presets.message)
});

function closeModal() {
    editState.deleting = false;
}

function deleteItem() {
    if (editState.deleting) {
        const index = form.schedule.findIndex(({ _id }) => _id === editState.deleting);
        if (index > -1) {
            const { pageRoute } = useRoute();
            if (!form.schedule[index].new) {
                form.delete(route("schedule.delete", [props.pet._id, editState.deleting]), {
                    onSuccess: () => {
                        useRoute(pageRoute.current);
                    },
                });
            }
            form.schedule.splice(index, 1);
            editState.editing = false;
        }
        editState.deleting = false;
    }
}

const saveSchedule = () => {
    const { pageRoute } = useRoute();
    form.transform((data) => {
        return {
            category: data.category,
            schedule: data.schedule.map(saveItem => {
                delete saveItem.new;
                return dt.objectLocalToUtc({ ...saveItem });
            })
        };
    }).patch(route("schedule.update", pageRoute.current.params), {
        preserveScroll: true,
        onSuccess: () => {
            presets.using = editState.editing = editState.manage = false;
            usePetAI(pet, { name: category });
            useRoute(pageRoute.current);
        },
        onError: () => {
            presets.using = false;
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

        <form class="border p-4 -mt-3 space-y-2 lg:p-6" @submit.prevent="saveSchedule">

            <template v-for="(item, index) in form.schedule" :key="item._id">

                <div v-if="form.schedule.length"
                     class="pt-2 font-bold flex justify-between border-b border-slate-700 dark:border-slate-500">
                    <div>Schedule {{ (form.schedule.length > 1 ? 1 + index : "") }}</div>
                    <div v-if="editState.editing === item._id" @click="editState.deleting=item._id">
                        <FAIcon class="text-base" name="trash"/>
                    </div>
                    <div v-else-if="!editState.editing || !form.isDirty" @click.prevent="editState.editing=item._id">
                        <FAIcon class="text-base" name="edit"/>
                    </div>
                </div>

                <FormScheduleItem
                        v-model:edit-state="editState"
                        v-model:schedule-item="form.schedule[index]"
                        :form="form"
                />

            </template>

            <div v-if="form.schedule.length && !form.isDirty" class="p3 text-center underline"
                 @click="presets.add=true">
                Add Another Schedule
            </div>

            <div v-if="presets.show || !form.schedule.length">
                <InputLabel :value="presets.title" class="text-center"/>
                <InputButtons id="presets" v-model="presets.new" :options="presets.list" class="gap-2"/>
            </div>

        </form>
    </section>

    <ModalConfirm :show="editState.deleting" @closeModal="closeModal" @confirm="deleteItem">
        Are you sure you want to delete this schedule?
    </ModalConfirm>

</template>
