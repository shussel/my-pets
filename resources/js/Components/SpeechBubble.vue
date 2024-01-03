<template>
    <div class="bubble">CSS Speech Bubble</div>
</template>

<script>
export default {
    name: "SpeechBubble"
}
</script>

<style scoped>
.bubble {
    position: relative;
    background: #dbe9fe;
    color: #111111;
    font-family: Arial, sans-serif;
    font-size: 13px;
    line-height: 20px;
    text-align: center;
    width: 180px;
    padding: 10px;
    /*height: 75px;*/
    border-radius: 10px;
    margin: 4px 0 0 15px;
}

.bubble:after {
    content: '';
    position: absolute;
    display: block;
    width: 0;
    z-index: 1;
    border-style: solid;
    border-color: transparent #dbe9fe;
    border-width: 9px 22px 9px 0;
    top: 51%;
    left: -20px;
    margin-top: -10px;
}

</style>
