<script setup>
  import { ref } from 'vue';
  import BaseTab from './BaseTab.vue';
  const props = defineProps({
    tabs: Array,
    currentIndex: {
      type: Number,
      default: 0
    },
  })
  const emits = defineEmits(
    ['changeCurrentIndex']
  )

  // const activeIndex = ref(props.currentIndex)

  const handleClick = (index) => {
    // activeIndex.value = index
    emits('changeCurrentIndex', index)
  }
</script>
<template>
  <!-- <div class="sm:hidden">
    <label for="tabs" class="sr-only">Select your country</label>
    <select id="tabs"
      class="
      bg-gray-50
        border 
        border-gray-300 
        text-gray-900 
        text-sm 
        rounded-lg 
        focus:ring-blue-500 
        focus:border-blue-500 
        block
        w-full
        p-2.5
        dark:bg-gray-700 
        dark:border-gray-600 
        dark:placeholder-gray-400 
        dark:text-white 
        dark:focus:ring-blue-500 
        dark:focus:border-blue-500
      "
    >
      <option>Settings</option>
      <option>Profile</option>
    </select>
  </div> -->
  <ul
    class="
      flex
      text-sm
      font-medium
      text-center 
      text-gray-500 
      divide-x 
      divide-gray-200 
      rounded-lg 
      shadow
      dark:divide-gray-700 
      dark:text-gray-400
      max-w-min
    "
  >
    <base-tab 
      v-for="(tab, index) in tabs"
      :active="props.currentIndex === index"
      :first="index === 0"
      :last="index === tabs.length - 1"
      :title="tab.title"
      :index="index"
      @on-click="handleClick"
    />
  </ul>
</template>