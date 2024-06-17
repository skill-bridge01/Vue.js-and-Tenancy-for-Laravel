<script setup>
import { ref, reactive } from "vue";

const props = defineProps({
    default: String,

    digitCount: {
        type: Number,
        required: true,
    },
});

const digits = reactive([]);

if (props.default && props.default.length === props.digitCount) {
    for (let i = 0; i < props.digitCount; i++) {
        digits[i] = props.default.charAt(i);
    }
} else {
    for (let i = 0; i < props.digitCount; i++) {
        digits[i] = null;
    }
}

const otpCont = ref(null);
</script>

<template>
    <div ref="otpCont">
        <input
            type="tex"
            class="digit-box"
            v-for="(el, ind) in digits"
            :key="el + ind"
            v-model="digits[ind]"
            :autofocus="ind === 0"
            :placeholder="ind + 1"
            maxlength="1"
        />
    </div>
</template>

<style scoped>
.digit-box {
    height: 3rem;
    width: 3rem;
    /* border: 1px solid black; */
    display: inline-block;
    border-radius: 5px;
    margin: 5px;
    padding: 15px;
    font-size: 2rem;
}

.digit-box:focus {
    outline: 2px solid black;
}

input,
input:focus,
input:active,
textarea,
textarea:focus,
textarea:active {
    border: none;
    -webkit-transition-delay: 9999s;
    transition-delay: 9999s;
    -webkit-box-shadow: 0 0 0 30px black inset !important;
    box-shadow: 0 0 0 30px rgb(211, 193, 193) inset !important;
}
</style>
