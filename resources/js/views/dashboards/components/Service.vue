<script setup>
import { storeToRefs } from 'pinia'
import { useServicesStore } from '@/store/pinia/services'
import { usePiecesStore } from '@/store/pinia/pieces'
import { useCartsStore } from '@/store/pinia/carts'
import { computed } from 'vue';

const props = defineProps({
  service: {
    type: Object,
  },

})

const serviceStore = useServicesStore()
const { selectedService } = storeToRefs(useServicesStore())

const cartsStore = useCartsStore()
const { carts, selectedCart } = storeToRefs(useCartsStore())

const piecesStore = usePiecesStore()
const { selectedPiece } = storeToRefs(usePiecesStore())

const emits = defineEmits([
  'handleClickService'
])

const selected = computed(() => {
  return selectedService?.id === props.service?.id
})

const handleClick = () => {
  emits('handleClickService', props.service.id)
  serviceStore.setSelectedService(props.service)
  console.log(selectedPiece.value.id)
  const cart = {
    pieceTitle: selectedPiece.value.piece_title,
    pieceId: selectedPiece.value.id,
    service: props.service,
    count: 1,
    discount: 0
  }

  cartsStore.addNewCart(cart);
}

</script>
<template>
  <ServiceButton
    @handle-click="handleClick" 
    :selected="selectedService?.id === props.service?.id"
    :checked="props.service.is_checked > 0"
  >
  

    <span class="widget-label" :class="selectedService?.id === props.service?.id ? 'text-main' : ''">{{ props.service?.services_title }}</span>
    <img
      :class="selectedService?.id === props.service?.id ? 'selected-image' : 'unselected-image'"
      :src="`/images/home/remove_stain.svg`"
      alt=""
    />
  </ServiceButton>
</template>
<style lang="scss" scoped>

.selected-image {
  -webkit-filter: invert(55%) sepia(50%) saturate(460%) hue-rotate(149deg) brightness(97%) contrast(99%);
  filter: invert(55%) sepia(50%) saturate(460%) hue-rotate(149deg) brightness(97%) contrast(99%);
}
  .widget-label {
    font-family: 'Readex Pro';
    font-style: normal;
    font-weight: 400;
    font-size: 16px;
    line-height: 20px;
    text-align: center;

    /* Neuturals/Medium Dark */

    color: #484860;
  }
</style>