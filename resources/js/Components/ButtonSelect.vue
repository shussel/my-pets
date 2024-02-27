<script setup>
defineProps({
    selected: {
        type: Boolean,
        default: false,
    },
    selection: {
        type: String,
    },
    singleChoice: {
        type: Boolean,
        default: false,
    }
});
</script>

<template>
    <button
            v-if="selected"
            class="px-3 py-2 inline-flex justify-between items-center text-center w-full
            transition ease-in-out duration-150
            border border-slate-300 rounded-md shadow-sm
                dark:border-slate-600
            text-slate-700 text-base sm:text-base leading-6 lowercase
                dark:text-slate-200
            bg-white
                dark:bg-slate-900
            hover:bg-slate-100
                dark:hover:bg-slate-900 dark:hover:text-slate-300
            focus:bg-slate-100 focus:border-lt/50 focus:ring-lt/50 focus:border-2 focus:outline-none
                dark:focus:border-lt/50 dark:focus:ring-lt/50
            disabled:opacity-25">
        <div>
            <slot/>
        </div>
        <div>{{ singleChoice ? "." : ">" }}</div>
    </button>
    <button
            v-else-if="!selection"
            class="px-1 py-2 sm:px-2 inline-flex justify-center items-center text-center
            transition ease-in-out duration-150
            border border-slate-300 rounded-md shadow-sm
                dark:border-slate-600
            text-slate-700 text-base sm:text-base leading-6 lowercase
                dark:text-slate-400
            bg-slate-50
                dark:bg-slate-900
            hover:bg-slate-200 hover:border-lt/50 hover:ring-lt/50
                dark:hover:text-slate-300
            focus:bg-slate-100 focus:border-lt/50 focus:ring-lt/50 focus:border-2 focus:outline-none
                dark:focus:border-lt/50 dark:focus:ring-lt/50
            disabled:opacity-25"
    >
        <slot/>
    </button>
</template>
