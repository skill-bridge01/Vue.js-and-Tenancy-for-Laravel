<script setup>
import { computed, ref } from 'vue'
import { INVOICE_STATUS } from '@/constants.js'
const props = defineProps({
  text: {
    type: String,
    default: 'completed'
  },
  bgColor: {
    type: String,
    default: 'bg-brandGreen/20'
  },
  fontColor: {
    type: String,
    default: 'text-brandGreen'
  },
  status: {
    type: Number,
    default: INVOICE_STATUS.IN_PROGRESS
  }
})

const statusLabel = computed(() => {
  switch (props.status) {
    case INVOICE_STATUS.IN_PROGRESS:
      return 'In Progress';
    case INVOICE_STATUS.READY_TO_PICK_UP:
      return 'Ready to pick'
    case INVOICE_STATUS.DELIVERED:
      return 'Delivered';
    default:
      break;
  }
})
</script>
<template>
  <div >
    <span
      class="
        rounded-md 
        text-right
        text-sm
        font-readex
        p-1
      "
      :class="`${props.bgColor} ${props.fontColor}`"
    >
      {{ statusLabel }}
    </span>
  </div>
</template>