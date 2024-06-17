<script setup>
import { ref } from "vue"
import { mdiPlus , mdiTrashCan } from "@mdi/js";
import { useI18n } from 'vue-i18n'
import BaseButton from "@/components/BaseButton.vue";

const props = defineProps({

})

const emits = defineEmits([
  'handleClickNewPiece'
])


const { t } = useI18n();
const isModalActive = ref(false);

</script>
<template>
  <div>
    <BaseButton
      :label="t('users.create')"
      labelColor="text-white"
      :icon="mdiPlus"
      color="text-white"
      bgColor="!bg-main"
      border="border"
      outline
      borderColor="brandBorder"
      @click="emits('handleClickNewPiece')" 
    />
  </div>
</template>