<script setup>
import { ref } from 'vue'

const selected = ref('card')

const props = defineProps({
  label: {
    type: String,
    default: '',
  },
  options: {
    type: [Object],
    default: []
  }
})
const emits = defineEmits(['update:selected'])

const handleSelectionChange = () => {
  emits('update:selected', selected.value)
}

</script>
<template>
  <div>
    <label for="countries" class="block mb-2 text-xs font-semibold text-gray-600 dark:text-white">{{ props.label }}</label>
    <select id="countries" v-model="selected" @change="handleSelectionChange" 
      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
      <option v-for="m in options" :value="m.value">{{ m.label }}</option>
    </select>
  </div>
</template>