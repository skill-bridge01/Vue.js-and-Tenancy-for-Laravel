<script setup>
import Piece from './Piece.vue';
const props = defineProps({
  pieces: {
    type: Array,
    default: []
  },

  selectedPiece: {
    type: Object,
    default: null
  }
})
const emits = defineEmits([
  'setActivePiece'
])
const handleClickPiece = (piece) => {
  console.log(`selected pieceId: ${piece?.id}`)
  emits('setActivePiece', piece)
}
</script>
<template>
  <div class="flex justify-end gap-4 flex-wrap">
    <Piece v-for="piece in pieces" :key="piece?.id" :piece="piece" @handle-click-piece="handleClickPiece"/>
  </div>
</template>
<style lang="scss" scoped>
    
</style>