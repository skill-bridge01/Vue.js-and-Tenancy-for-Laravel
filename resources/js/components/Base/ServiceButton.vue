<script setup>
import FavoriteIcon from '@/assets/icons/favorite.svg'
const props = defineProps({
    noPadding: Boolean,
    selected: Boolean,
    checked: Boolean,
})
const emit = defineEmits([
  'handleClick'
])
const handleClick = () => {
  emit('handleClick')
}
</script>
<template>
  <div
    class="
      relative
      card 
      flex 
      flex-row 
      bg-white 
      dark:bg-gray-800 
      dark:text-white 
      cursor-pointer 
      justify-center 
      items-center 
      mt-2
    "
    :class="props.selected ? 'border border-main selected' : ''"
    @click="handleClick"
  >
      <slot name="cardHeader" />
      <div class="card-body" :class="props.noPadding ? 'noPadding' : ''">
          <slot />
      </div>
      <div class="absolute top-2 right-1" v-if="props.checked">
        <FavoriteIcon />
      </div>
    </div>
</template>

<style lang="scss" scoped>
.card {
    border-radius: 10px;
    box-shadow: 0 4px 20px 1px rgb(0 0 0 / 6%), 0 1px 4px rgb(0 0 0 / 8%);
    // box-shadow:rgb(149 157 165 / 20%) 0px 8px 24px;
    // width: 204px;
    &.selected {
      background: #F5FAFF;
    }
    .card-body {
      -webkit-box-flex: 1;
      -ms-flex: 1 1 auto;
      flex: 1 1 auto;
      padding: 1.25rem;
      display: flex;
      justify-content: center;
      flex-direction: row;
      gap: 10px;
      align-items: center;
      &.noPadding {
          padding: 0 !important;
      }
    }
}
</style>
