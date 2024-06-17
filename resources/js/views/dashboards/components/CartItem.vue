<script setup>
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import ArrowDownIcon from '@/assets/icons/arrow_up_grey.svg'
import UrgentWashIcon from '@/assets/icons/urgent_wash.svg'
import MinusGreenIcon from '@/assets/icons/minus_green.svg'
import PlusGreenIcon from '@/assets/icons/plus_green.svg'
import PercentBorderedRedIcon from '@/assets/icons/percent_borderd_red.svg'

import { useCartsStore } from '@/store/pinia/carts'
// import { useCustomersStore } from '@/store/pinia/customers'
// import { useServicesStore } from '@/store/pinia/services'
import { storeToRefs } from 'pinia';

const { t } = useI18n()

const props = defineProps({
  cart: Object
})

const cartsStore = useCartsStore()
const { carts, selectedCart, note } = storeToRefs(useCartsStore())

const count = ref(1)
const expanded=ref(false)
const note1 = ref('')
const discount1 = ref('')

const handleChangeDiscount = (event) => {
  discount1.value=event.target.value
  cartsStore.changeCartDiscount(props.cart, event.target.value)
}

const handleChangeNote = (event) => {
  console.log("Note", props.cart.service.id, event.target.value)
  note1.value=event.target.value
  cartsStore.changeCartNote(props.cart, event.target.value)
}

const handleClickMinusButton = () => {
  count.value -= 1
  cartsStore.changeCartCount(props.cart, -1)
}

const handleClickPlusButton = () => {
  console.log('props.cart', props.cart. service.id)
  count.value += 1
  cartsStore.changeCartCount(props.cart, 1)
}

const toggleExpand = () => {
  expanded.value = !expanded.value
}

const handleClickRemoveCart = () => {
  cartsStore.removeCart(props.cart)
}

</script>
<template>
  <div class="flex justify-between items-center px-6 py-3 cart-item-wrapper">
    <div class="flex items-center gap-4">
      <button type="button" @click="handleClickRemoveCart">
        <img class="" :src="`/images/home/close_grey.svg`" alt="" />
      </button>
      <span class="price-label">{{ t('dashboard.currency') }}</span>
      <span class="price-label">{{ cart?.service.pivot.price }}</span>
    </div>
    <div class="flex gap-2">
      <div class="">
        <div class="widget-name-label">{{ cart?.pieceTitle }}</div>
        <div class="quantity-wrapper font-readex text-main mt-2 p-2 font-semibold text-sm">{{ t('dashboard.count') }} {{ cart?.count }}</div>
      </div>
      <div>
        <img class="" :src="`/images/home/trousers.svg`" alt="" />
      </div>
      <div class="flex justify-center items-center cursor-pointer" @click="toggleExpand">
        <img v-if="!expanded" class="" :src="`/images/home/arrow_down_grey.svg`" alt="" />
        <img v-else class="" :src="`/images/home/arrow_up_grey.svg`" alt="" />
      </div>
    </div>
  </div>
  <div class="flex justify-end items-center mr-7 ml-4 px-4 py-3 gap-2 urgent-wash-wrapper">
    <p class="urgent-wash-label">{{ cart?.service.services_title }}</p>
    <UrgentWashIcon />
  </div>
  <div v-if="expanded" class="flex justify-between items-center mr-5 ml-4 py-3 gap-2 urgent-wash-wrapper">
    <div class="">
      <div class="text-right description-label">
        {{ t('dashboard.discount') }}
      </div>
      <div class="flex items-center justify-between gap-1">
        <span>ريال</span>
        <PercentBorderedRedIcon />
        <input 
          type="text" 
          placeholder="0" 
          :value="discount1"
          class="border px-1 py-1 rounded-lg text-right focus:outline-none max-w-[140px] h-9"
          @input="handleChangeDiscount"
        />
      </div>
    </div>
    <div>
      <div class="text-right description-label">
        {{ t('dashboard.pieceCount') }}
      </div>
      <div class="flex items-center justify-between w-32 border rounded-lg overflow-hidden">
        <div class="minus-button cursor-pointer" @click="handleClickMinusButton">
          <MinusGreenIcon />
        </div>
        <div>{{ cart?.count || count }}</div>
        <div class="minus-button cursor-pointer" @click="handleClickPlusButton">
          <PlusGreenIcon />
        </div>
      </div>
    </div>
  </div>
  <div class="flex" v-if="expanded">
    <input 
      type="text" 
      placeholder="Note" 
      :value="note1"
      class="border px-4 py-2 rounded-lg text-right focus:outline-none w-full mx-5"
      @input="handleChangeNote"
    />
  </div>
</template>
<style lang="scss" scoped>
.cart-item-wrapper {
  .price-label {
    font-family: 'Readex Pro';
    font-style: normal;
    font-weight: 500;
    font-size: 16px;
    line-height: 20px;
    /* identical to box height */

    text-align: right;

    /* Neutrals/Black */

    color: #0D0D0D;
  }

  .widget-name-label {
    font-family: 'Readex Pro';
    font-style: normal;
    font-weight: 400;
    font-size: 14px;
    line-height: 18px;
    /* identical to box height */

    text-align: right;

    /* Neutrals/Black */

    color: #0D0D0D;
  }

  .quantity-wrapper {
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    width: 72px;
    height: 24px;

    background: #EDF9FF;
    border-radius: 4px;
  }


}
.urgent-wash-wrapper {
  background: #FAFCFE;
  .urgent-wash-label {
    font-family: 'Readex Pro';
    font-style: normal;
    font-weight: 500;
    font-size: 16px;
    line-height: 20px;
    /* identical to box height */

    display: flex;
    align-items: center;
    text-align: center;

    /* Brand/Main */

    color: #45A9CB;
  }
}

.minus-button {
  display: flex;
  flex-direction: row;
  justify-content: center;
  align-items: center;
  padding: 0px 8px;

  width: 32px;
  height: 36px;

  /* Brand/Green Light */

  background: #EDF8EC;
}

.description-label {
  font-family: 'Readex Pro';
  font-style: normal;
  font-weight: 300;
  font-size: 14px;
  line-height: 18px;
  /* identical to box height */
  /* Neutrals/Medium Dark */

  color: #484860;
}

</style>