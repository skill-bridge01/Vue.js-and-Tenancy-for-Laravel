<script setup>
import { ref, watchEffect } from "vue";

const props = defineProps({
    length: {
        type: Number,
        default: 2,
    },
    onComplete: {
        type: Function,
        required: true,
    },
});

const OTP = ref(Array(props.length).fill(""));
const inputRefs = ref([]);

watchEffect(() => {
    OTP.value = Array(props.length).fill(""); // Reset OTP array size when props.length changes
});

const handleTextChange = (event, index) => {
    const input = event.target.value;
    OTP.value[index] = input;

    if (input.length === 1 && index < props.length - 1) {
        nextTick(() => inputRefs.value[index + 1].focus());
    }

    if (input.length === 0 && index > 0) {
        inputRefs.value[index - 1].focus();
    }

    if (OTP.value.every((digit) => digit !== "")) {
        props.onComplete(OTP.value.join(""));
    }
};
</script>

<template>
    <div class="grid grid-cols-6 gap-1">
        <input
            v-for="index in length"
            :key="index"
            type="text"
            maxlength="1"
            v-model="OTP[index - 1]"
            @input="handleTextChange($event, index - 1)"
            ref="inputRefs"
            class="border border-solid border-slate-500 focus:border-blue-600 p-5 outline-none rounded-lg"
            :style="{ marginRight: index === length ? '0' : '10px' }"
        />
    </div>
</template>
