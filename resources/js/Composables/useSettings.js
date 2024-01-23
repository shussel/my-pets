import { ref, onMounted, watch, computed, toRaw } from "vue";

const defaultSettings = {};

const options = {
    darkMode: [
        { value: "light", label: "Light" },
        { value: "auto", label: "Auto" },
        { value: "dark", label: "Dark" }
    ],
    theme: [
        { value: "default", label: "Default", color: "#cbd5e1" },
        { value: "red", label: "Red", color: "#fca5a5" },
        { value: "blue", label: "Blue", color: "#93c5fd" },
        { value: "green", label: "Green", color: "#86efac" },
        { value: "purple", label: "Purple", color: "#c4b5fd" },
        { value: "yellow", label: "Yellow", color: "#fde047" }
    ]
};

// placing settings ref here makes all instances use the same settings reactively
const settings = ref(defaultSettings);

// watching globally to save setting changes
watch(() => settings.value, () => {
    if (Object.keys(toRaw(settings.value)).length) {
        localStorage.setItem("settings", JSON.stringify(settings.value));
    }
}, { deep: true });

export default function useSettings() {

    // needed to refresh settings input v-models
    const settingsKey = ref(0);

    // consider system preferences along with dark mode setting
    const useDark = computed(() => {
        return (settings.value.darkMode === "dark") || ((settings.value.darkMode !== "light") && window.matchMedia("(prefers-color-scheme: dark)").matches);
    });

    // get saved settings once mounted
    onMounted(() => {
        const savedSettings = localStorage.getItem("settings");
        if (savedSettings) {
            settings.value = JSON.parse(localStorage.getItem("settings"));
            //settingsKey.value++;
        }
    });

    return { settings, settingsKey, useDark, options };
}