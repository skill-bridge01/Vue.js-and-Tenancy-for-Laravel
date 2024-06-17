<script setup>
import { computed, ref } from 'vue'
import { useI18n } from 'vue-i18n'

import CardBoxModal from "@/components/CardBoxModal.vue";
import { useCartsStore } from '@/store/pinia/carts'
import { storeToRefs } from 'pinia';

const { t } = useI18n()

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
const { note } = storeToRefs(useCartsStore())

const _note = ref(note)


const handleChangeNote = (event) => {
  _note.value = event.target.value
}

const confirm = () => {
  console.log('confirm')
  cartsStore.updateNote(_note.value)
}
</script>

<template>
  <card-box-modal
    v-model="active"
    :title="t('dashboard.note')"
    :button-label="t('common.save')"
    button="bg-main"
    @confirm="confirm"
  >
    <div
      class="
        w-full
        px-4
        py-3                
        border 
        border-gray-100
        flex
        justify-end
        items-center
      "
    >
      <textarea
        class="
          w-full
          focus:outline-none
          text-right
        "
        :value="_note"
        placeholder="أحمد أحمد"
        @input="handleChangeNote"
      >
      </textarea>
    </div>
  </card-box-modal>
</template>

<style lang="scss" scoped>
</style>