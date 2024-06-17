<script setup>
import { ref } from "vue"
import { mdiPlus , mdiTrashCan } from "@mdi/js";
import { useI18n } from 'vue-i18n'
import BaseButton from "@/components/BaseButton.vue";

const props = defineProps({

})

const emits = defineEmits([
  'handleClickNewService'
])


const { t } = useI18n();
const isModalActive = ref(false);

</script>
<template>
  <div>
    <BaseButton
      :label="t('services.create')"
      labelColor="text-white"
      :icon="mdiPlus"
      color="text-white"
      bgColor="!bg-main"
      border="border"
      outline
      borderColor="brandBorder"
      @click="emits('handleClickNewService')" 
    />
  </div>
</template>