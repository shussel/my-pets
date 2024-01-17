<template>
    <button class="bone relative inline-flex text-center justify-center leading-none w-full text-xl font-bold text-white p-3 rounded-md"
            @click="$emit('click')">
        <span><slot/></span>
    </button>

    <!-- Filter: https://css-tricks.com/gooey-effect/ -->
    <svg height="0" style="visibility: hidden; position: absolute;" version="1.1" width="0"
         xmlns="http://www.w3.org/2000/svg">
        <defs>
            <filter id="goo">
                <feGaussianBlur in="SourceGraphic" result="blur" stdDeviation="10"/>
                <feColorMatrix in="blur" mode="matrix" result="goo"
                               values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9"/>
                <feComposite in="SourceGraphic" in2="goo" operator="atop"/>
            </filter>
        </defs>
    </svg>

</template>

<style>
.bone {
    filter: url('#goo');
}

.bone,
.bone::before,
.bone::after,
.bone span::before,
.bone span::after {
    background: #9ca3af;
}

.bone:hover,
.bone:hover::before,
.bone:hover::after,
.bone:hover span::before,
.bone:hover span::after {
    background: #d1d5db;
}

.bone::before,
.bone::after,
.bone span::before,
.bone span::after {
    content: "";
    width: 1.7em;
    height: 1.7em;
    position: absolute;
    display: inline-block;
    border-radius: 50%;
    z-index: -1;
}

/*top*/
.bone::before,
.bone span::before {
    top: 50%;
}

/*bottom*/
.bone::after,
.bone span::after {
    bottom: 50%;
}

/*right*/
.bone::before,
.bone::after {
    right: -10%;
}

/*left*/
.bone span::before,
.bone span::after {
    left: -10%;
}
</style>
