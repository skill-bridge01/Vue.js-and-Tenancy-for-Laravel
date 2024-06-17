<script setup>
import { ref, onMounted, watchEffect } from 'vue';
import { useI18n } from 'vue-i18n'

import { storeToRefs } from "pinia";
import SalesSection from './sections/SalesSection.vue';
import StatisticsSection from './sections/StatisticsSection.vue';
import BaseTabs from '../../components/BaseTabs.vue';
import { useStatisticStore } from '@/store/pinia/statistic.js';
import { getQueryByIndex } from '../../utils';
const { t, locale } = useI18n();

const { loading, currentTabIndex } = storeToRefs(useStatisticStore());

const tabs = ref([
  { active: false, title: t('analytics.tabs.allTime'), type: 'allTime' },
  { active: false, title: t('analytics.tabs.thisMonth'), type: 'thisMonth' },
  { active: true, title: t('analytics.tabs.today'), type: 'today' },
])

watchEffect(() => {
  tabs.value.forEach((tab) => {
    switch (tab.type) {
      case 'allTime':
        tab.title = t('analytics.tabs.allTime')
        break
      case 'thisMonth':
        tab.title = t('analytics.tabs.thisMonth')
        break
      case 'today':
        tab.title = t('analytics.tabs.today')
        break
      default:
          break
    }
  })
})
const saleTotal = ref([])
const tab = ''
const currentIndex = ref(0)
const statisticStore = useStatisticStore();
onMounted(() => {
  statisticStore.fetch(getQueryByIndex(currentTabIndex.value));
});
const handleChangeTabIndex = (index) => {
  currentIndex.value = index;
  statisticStore.setCurrentTabIndex(index);
  statisticStore.fetch(getQueryByIndex(index));
}
</script>
<template>
  <!-- Tab -->
  <div>
    <div class="flex justify-end">
      <BaseTabs :tabs="tabs" :current-index="currentIndex" @change-current-index="handleChangeTabIndex" />
    </div>
  </div>
  <!-- Line chart -->
  <SalesSection class="my-6" />
  <!-- Tables -->
  <StatisticsSection />
</template>
<style lang="scss" scoped></style>