<template>
  <div
    href="#"
    class="block p-2 border-transparent border-l-4 group-hover:border-blue-600 group-hover:bg-gray-100 text-right"
    @click="handleClickItem"
  >
    <p class="font-readex text-[#484860] text-base">{{ data.name }}</p>
    <p class="font-readex text-[#737F98] text-base font-light">{{ data.mobile }}</p>
</div>
</template>
<script setup>
const props = defineProps({
 data: Object
})

const emits = defineEmits([
  'clickCustomerRow'
])

const handleClickItem = () => {
  emits('clickCustomerRow', props.data)
} 
</script>