<script setup>
import { storeToRefs } from 'pinia'
import { usePiecesStore } from '@/store/pinia/pieces'
import { computed } from 'vue';

const props = defineProps({
  piece: {
    type: Object,
  },

})

const pieceStore = usePiecesStore()
const { selectedPiece } = storeToRefs(usePiecesStore())

const emits = defineEmits([
  'handleClickPiece'
])

// const selected = computed(() => {
//   console.log(selectedPiece?.id)
//   console.log(props.piece?.id)
//   // return selectedPiece?.id === props.piece?.id
//   return props.piece?.id > 0
// })

const handleClick = () => {
  // emits('handleClickPiece', props.piece)
  console.log('piece selected')
  pieceStore.setSelectedPiece(props.piece)
}

</script>
<template>
  <WidgetButton
    @handle-click="handleClick"
    :noPadding="false"
    :selected="selectedPiece?.id === props.piece?.id"
  >
    <img
      :class="selectedPiece?.id === props.piece?.id ? 'selected-image' : 'unselected-image'"
      :src="`/images/home/trousers.svg`"
      alt="" 
    />
    <span :class="selectedPiece?.id === props.piece?.id ? 'text-main' : ''" class="widget-label">{{ props.piece.piece_title }}</span>
  </WidgetButton>
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