<script setup>
import { useI18n } from 'vue-i18n'
import SearchWhiteIcon from '@/assets/icons/invoices/search_white.svg'
const { t } = useI18n();

const emits = defineEmits([
  'onClick'
])

const handleClick = () => {
  emits('onClick')
}

</script>
<template>
  <div class="flex rounded-lg justify-center items-center w-44 h-10 bg-main gap-3 cursor-pointer" @click="handleClick">
    <p class="font-readex text-white text-sm">{{ t('invoices.search') }}</p>
    <SearchWhiteIcon />
  </div>
</template>