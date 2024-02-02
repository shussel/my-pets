<script setup>
import Card from "@/Components/Card.vue";
import PetImage from "@/Components/PetImage.vue";
import ButtonDefault from "@/Components/ButtonDefault.vue";
import Food from "@/Pages/Pets/Settings/Food.vue";
import Poop from "@/Pages/Pets/Settings/Poop.vue";
import Sleep from "@/Pages/Pets/Settings/Sleep.vue";
import Behavior from "@/Pages/Pets/Settings/Behavior.vue";
import Identity from "@/Pages/Pets/Settings/Identity.vue";
import usePageTitle from "@/Composables/usePageTitle.js";
import useRoute from "@/Composables/useRoute.js";
import FAIcon from "@/Components/FAIcon.vue";

const props = defineProps({
    pet: {
        type: Object,
        required: true,
    },
    meta: {
        type: Object,
        default: {}
    },
});

usePageTitle("Pet Settings");

</script>

<template>
    <Card>
        <div class="flex justify-start gap-3 sm:gap-4 sm:p-1">
            <PetImage :pet="pet" class="w-[200px] h-[200px] max-h-[200px]"/>
            <div class="grow space-y-2">
                <h2 class="text-xl px-0">
                    <FAIcon :name="pet.species" class="-ml-[30px] dark:text-slate-400"/>
                    {{ pet.name }}
                </h2>
                <div class="flex justify-between items-end">
                    <div class="text-sm text-slate-500 dark:text-slate-300">Sex</div>
                    <div>{{ pet.sex }}</div>
                </div>
                <div class="flex justify-between items-end">
                    <div class="text-sm text-slate-500 dark:text-slate-300">Weight</div>
                    <div>{{ pet.weight }} lbs</div>
                </div>
                <div class="flex justify-between items-end">
                    <div class="text-sm text-slate-500 dark:text-slate-300">Born</div>
                    <div>{{
                            new Date(pet.birth_date).toLocaleDateString("en-us", {
                                month: "numeric",
                                day: "numeric",
                                year: "2-digit",
                            })
                        }}
                    </div>
                </div>
                <div class="flex justify-between items-end">
                    <div class="text-sm text-slate-500 dark:text-slate-300">Age</div>
                    <div>{{ pet.age }}</div>
                </div>
                <div class="text-center">
                    <ButtonDefault :class="{ 'opacity-25': disableButtons }" :disabled="disableButtons" class="m-2"
                                   @click="useRoute({ name: 'pets.edit', params: pet._id})">Edit
                    </ButtonDefault>
                </div>
            </div>
        </div>
    </Card>

    <Card>
        <Food :feedOptions="meta.settings.feed" :pet="pet"/>
    </Card>

    <Card>
        <Poop :pet="pet" :species="meta.species"/>
    </Card>

    <Card>
        <Sleep :pet="pet" :species="meta.species"/>
    </Card>

    <Card>
        <Behavior :behaviorOptions="meta.settings.behavior" :pet="pet"/>
    </Card>

    <Card>
        <Identity :identityOptions="meta.settings.identity" :pet="pet"/>
    </Card>

</template>
