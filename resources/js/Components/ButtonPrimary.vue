<template>
    <button class="inline-flex justify-center items-center text-center px-4 py-2
    transition ease-in-out duration-150
    border border-transparent rounded-md shadow-sm
    text-white text-xs uppercase tracking-wider font-semibold
        dark:text-black
    bg-slate-900
        dark:bg-slate-300
    hover:bg-slate-600
        dark:hover:bg-slate-200
    focus:bg-slate-800 focus:border-lt/50 focus:border-2 focus:outline-none
        dark:focus:bg-slate-200
    active:bg-slate-800
        dark:active:bg-slate-100
    disabled:text-black disabled:bg-slate-300 disabled:opacity-25"
    >
        <slot/>
    </button>
</template>
