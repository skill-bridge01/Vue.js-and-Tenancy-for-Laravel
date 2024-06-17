<script setup>
import { computed, ref } from 'vue'
import { useI18n } from 'vue-i18n'

import CardBoxModal from "@/components/CardBoxModal.vue";
import PercentBorderedRedIcon from '@/assets/icons/percent_borderd_red.svg'
import { useCartsStore } from '@/store/pinia/carts'
import { storeToRefs } from 'pinia';

const { t } = useI18n();

const props = defineProps({
  modalActive: Boolean
})

const emits = defineEmits([
  'toggleModalActive'
])

const active = computed( {
  get () {
    return props.modalActive
  },
  set (val) {
    emits('toggleModalActive', val)
  }
})

const cartsStore = useCartsStore()
const { discount } = storeToRefs(useCartsStore())

const _discount = ref(discount)

const handleChangeDiscount = (event) => {
  _discount.value = parseFloat(event.target.value) || 0
}

</script>

<template>
  <card-box-modal
    v-model="active"
    :title="t('dashboard.addDiscount')"
    :button-label="t('common.save')"
    button="bg-main"
  >
    <div
      class="
        w-full
        px-4
        py-3                
        flex
        justify-center
      "
    >
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
            class="border px-1 py-1 rounded-lg text-right focus:outline-none h-9"
            :value="_discount"
            @input="handleChangeDiscount"
          />
        </div>
      </div>
    </div>
  </card-box-modal>
</template>

<style lang="scss" scoped>
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