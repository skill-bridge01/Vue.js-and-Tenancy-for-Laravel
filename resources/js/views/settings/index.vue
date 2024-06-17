<script setup>
import { ref } from 'vue';
import { useI18n } from 'vue-i18n'
import BaseTabs from '../../components/BaseTabs.vue';
import ProfilePannel from './components/ProfilePannel.vue';
import SettingsPannel from './components/SettingsPannel.vue';

const { t } = useI18n();

const tabs = [
  { active: true, title: t('account.settings') },
  // { active: false, title: t('account.management') },
]
const currentIndex = ref(0)

const handleChangeTabIndex = (index) => {
  currentIndex.value = index
}
</script>
<template>
  <div class="flex flex-col items-end">
    <div class="flex justify-end">
      <!-- <BaseTabs :tabs="tabs" :current-index="currentIndex" @change-current-index="handleChangeTabIndex" /> -->
    </div>

    <div v-if="currentIndex === 0" class="flex flex-col items-end w-fit">
      <SettingsPannel />
    </div>
    <div v-else class="flex flex-col items-end w-fit">
      <!-- <ProfilePannel /> -->
    </div>
  </div>
</template>