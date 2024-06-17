<script setup>
import { computed, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import CardBoxModal from "@/components/CardBoxModal.vue";
import router from '../router';
import { useAuthStore } from '@/store/pinia/auth'
import { storeToRefs } from 'pinia'

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

const {token} = storeToRefs(useAuthStore())
const { setToken, setUser} = useAuthStore()

const confirm = () => {
  router.replace({ name: 'Login'}).then(() => {
    console.log('logout')
    setToken(null)
    setUser(null)
  })
}
</script>

<template>
  <card-box-modal
    v-model="active"
    :title="t('modal.logout.title')"
    :button-label="t('modal.logout.signout')"
    button="bg-main"
    :has-cancel="true"
    justify-type="justify-center"
    @confirm="confirm"
  >
  <div
      class="
        w-full
        px-4
        py-3                
        flex
        justify-end
        items-center
      "
    >
    </div>
  </card-box-modal>
</template>

<style lang="scss" scoped>
</style>