import { ref, reactive, computed } from "vue";

export default function usePresets(presetData) {

    const presets = reactive({
        title: computed(() => presetData.value.title || "Presets"),
        list: computed(() => presetData.value.list || []),
        preset: computed(() => presetData.value.preset || []),
        default: computed(() => presetData.value.default || {}),
        has: computed(() => presets.list.length > 1),
        show: computed(() => presets.has && presets.add),
        add: ref(false),
        new: ref(false),
        using: ref(false),
        message: computed(() => presets.using ? "Suggested Values - Edit or" : null),
    });

    function getPreset(usePreset) {
        return { ...presets.default, ...presets.preset[usePreset] };
    }

    function newPreset(preset) {
        presets.new = null;
        presets.add = null;
        presets.using = true;
        return getPreset(preset);
    }

    return {
        presets,
        getPreset,
        newPreset
    };
}
