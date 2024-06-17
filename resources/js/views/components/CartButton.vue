<script setup>
import { ref } from 'vue';
import { CONTROL_BUTTON_TYPE } from '@/constants.js'
import AddNoteModal from '../../components/AddNoteModal.vue';
import AddDiscountModal from '../../components/AddDiscountModal.vue';
import { useCartsStore } from '@/store/pinia/carts'
import { useCustomersStore } from '@/store/pinia/customers'
const props = defineProps({
  image: String,
  title: String,
  noBordder: Boolean,
  type: Number,
})

const noteModalActive = ref(false)
const discountModalActive = ref(false)
const cartsStore = useCartsStore()
const customerStore = useCustomersStore()

const handleClickButton = () => {
  console.log('button click')
  noteModalActive.value = false
  discountModalActive.value = false

  switch (props.type) {
    case CONTROL_BUTTON_TYPE.ADD_DISCOUNT:
      discountModalActive.value = true
      break;
    case CONTROL_BUTTON_TYPE.ADD_NOTE:
      noteModalActive.value = true
      break;
    case CONTROL_BUTTON_TYPE.CANCEL:
      cartsStore.clear()
      customerStore.clear()
      break;
    case CONTROL_BUTTON_TYPE.HOLD:
      
      break;  
    default:
      break;
  }
}

const toggleNoteModalActive = (val) => {
  noteModalActive.value = val
}

const toggleDiscountModalActive = (val) => {
  discountModalActive.value = val
}
</script>

<template>
  <AddNoteModal :modal-active="noteModalActive" @toggle-modal-active="toggleNoteModalActive" />
  <AddDiscountModal :modal-active="discountModalActive" @toggle-modal-active="toggleDiscountModalActive" />

  <div 
    class="flex flex-col justify-center items-center flex-1"
    :class="props.noBordder ? '' : 'border-r'"
  >
    <button type="button" @click="handleClickButton">
      <img class="" :src="props.image" alt="" />
    </button>
    <p class="font-medium text-sm font-readex">
      {{ props.title }}
    </p>
  </div>
</template>