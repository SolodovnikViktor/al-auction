<script setup>
import {ref} from 'vue'

const SIZE = 10;

const currentKey = ref(0);

function onMouseLeave() {
}

function onMouseEnter(key) {
    currentKey.value = key;
}

</script>

<template>
    <div class="slider">
        <div class="slider__element slider__element--main active">6</div>
        <div class="slider__elements">
            <div v-for="key in SIZE" :key="key" class="slider__element" :class="{ active: key === currentKey }">{{
                    key
                }}
            </div>
        </div>
        <div class="slider__ghosts">
            <div
                v-for="key in SIZE"
                :key="key"
                @mouseenter="onMouseEnter(key)"
                @mouseleave="onMouseLeave"
                class="slider__ghost"
                :class="{ active: key === currentKey }"
                :style="{width: 100 / SIZE + '%'}"
            ></div>
        </div>
    </div>
</template>

<style scoped>
.slider {
    height: 300px;
    width: 300px;
    background-color: red;
    position: relative;
    display: flex;
}

.slider__elements {
    display: none;
}

.slider:hover .slider__elements {
    display: block;
}

.slider__element {
    color: #fff;
    font-size: 30px;
    height: 100%;
    width: 100%;
    display: none;
    position: absolute;
    top: 0;
    left: 0;
    align-items: center;
    justify-content: center;
}

.slider__element.slider__element--main {
    display: flex;
}

.slider:hover .slider__element.slider__element--main {
    display: none;
}

.slider__element.active {
    display: flex;
}

.slider:hover .slider__ghosts {
    display: flex;
}

.slider__ghosts {
    display: flex;
    padding: 0 5px;
    width: 100%;
    gap: 5px;
    display: none;
}

.slider__ghost {
    position: relative;
    border-bottom: 4px solid rgba(255, 255, 255, .5);
    height: calc(100% - 8px);
}

.slider__ghost.active {
    border-bottom-color: #00ffff;
}
</style>

