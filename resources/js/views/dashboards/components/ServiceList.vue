<script setup>
import Service from './Service.vue';
const props = defineProps({
  services: {
    type: Array,
    default: []
  },

  selectedService: {
    type: Object,
    default: null
  }
})

const handleClickService = (serviceId) => {
  // console.log(`selected serviceId: ${serviceId}`)
}
</script>
<template>
  <div class="flex justify-end gap-4 flex-wrap">
    <Service
      v-for="service in services"
      :key="service?.id"
      :service="service"
      @handle-click-service="handleClickService"
    />
  </div>
</template>
<style lang="scss" scoped>
    
</style>