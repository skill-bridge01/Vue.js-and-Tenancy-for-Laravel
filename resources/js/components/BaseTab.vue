<script setup>
import { computed } from 'vue';

const props = defineProps({
  active: Boolean,
  title: String,
  first: Boolean,
  last: Boolean,
  index: Number,
})

const emits = defineEmits(
  ['onClick']
)

const handleClick = () => {
  emits('onClick', props.index)
}
const componentClass = computed(() => {
  const base = [
    "inline-block",
    "w-full",
    "p-4",
    "focus:outline-none",
    " font-readex font-medium text-base"
  ]

  if (props.active) {
    base.push([
      "text-white",
      "bg-main",
      "active",
      "dark:bg-gray-700",
      "dark:text-white",
      "",
    ])
  } else {
    base.push([
      "bg-white",
      "hover:text-gray-700",
      "hover:bg-gray-50",
      "dark:hover:text-white",
      "dark:bg-gray-800",
      "dark:hover:bg-gray-700",
    ])
  }

  if (props.first) {
    base.push([
      "rounded-l-lg",
    ])
  }

  if (props.last) {
    base.push([
      "rounded-r-lg",
    ])
  }
  return base;
})
</script>
<template>
  <li class="w-28 sm:w-40">
    <a
      href="#"
      :class="componentClass"
      class="text-sm sm:text-base"
      @click="handleClick"
    >
      {{ props.title }}
    </a>
  </li>
</template>