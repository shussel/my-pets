<template>
    <button
            class="inline-flex justify-center items-center text-center px-4 py-2
            transition ease-in-out duration-150
            border border-slate-300 rounded-md shadow-sm
                dark:border-slate-600
            text-slate-700 font-semibold text-xs uppercase tracking-wider
                dark:text-slate-400
            bg-white
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
