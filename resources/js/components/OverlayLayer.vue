<script setup>

defineProps({
  zIndex: {
    type: String,
    default: "z-50",
  },
  type: {
    type: String,
    default: "flex",
  },
  overflowHidden: {
    type: Boolean,
    default: true
  }
});

const emit = defineEmits(["overlay-click"]);

const overlayClick = (event) => {
  emit("overlay-click", event);
};

</script>

<template>
  <div
    :class="[type, zIndex, overflowHidden ? ' overflow-hidden' : 'overflow-x-hidden overflow-y-scroll']"
    class="items-center flex-col justify-center fixed inset-0"
  >
    <transition
      enter-active-class="transition duration-150 ease-in"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        class="absolute bg-gradient-to-tr opacity-90 dark:from-gray-700 dark:via-gray-900 dark:to-gray-700 from-white via-gray-100 to-white"
        :class="overflowHidden ? 'inset-0' : 'inset-0'"
        @click="overlayClick"
      />
    </transition>
    <transition
      enter-active-class="transition duration-100 ease-out"
      enter-from-class="transform scale-95 opacity-0"
      enter-to-class="transform scale-100 opacity-100"
      leave-active-class="animate-fade-out"
    >
      <slot />
    </transition>
  </div>
</template>
