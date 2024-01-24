<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import ButtonPrimary from "@/Components/ButtonPrimary.vue";
import InputButtons from "@/Components/InputButtons.vue";
import useSettings from "@/Composables/useSettings.js";

const emit = defineEmits(["closeSettings"]);

const { settings, settingsKey, options } = useSettings();

</script>

<template>
    <section>
        <h2 class="ml-2">Settings</h2>
        <form class="border p-4 pt-5 -mt-3 space-y-6 lg:p-6 lg:pb-3" @submit.prevent="saveSettings">
            <div class="flex flex-wrap gap-2">
                <div class="grow min-w-1/2">
                    <InputLabel for="theme" value="Theme Color"/>
                    <InputButtons id="theme" :key="settingsKey" v-model="settings.theme" :options="options.theme"
                                  class="gap-2"/>
                </div>
                <div class="grow min-w-1/2">
                    <InputLabel for="darkMode" value="Dark Mode"/>
                    <InputButtons id="darkMode" :key="settingsKey" v-model="settings.darkMode"
                                  :options="options.darkMode"
                                  class="gap-2"/>
                </div>
            </div>
            <div class="text-center">
                <ButtonPrimary @click.prevent="emit('closeSettings')">Close</ButtonPrimary>
            </div>
        </form>
    </section>
</template>
