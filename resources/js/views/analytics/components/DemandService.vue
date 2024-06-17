<script setup>
import { ref, watch, watchEffect } from "vue";
import { useI18n } from 'vue-i18n'
import { storeToRefs } from "pinia";
import { useStatisticStore } from '../../../store/pinia/statistic';
import { getDataLabelByQuery, getServiceDataFromStatistics } from '../../../utils'
const { t } = useI18n();

const { mostServicedItems, loading, currentTabIndex } = storeToRefs(useStatisticStore());

const optionsRef = ref({
  chart: {
    width: 380,
    type: 'donut',
  },
  dataLabels: {
    enabled: false
  },
  labels: getServiceDataFromStatistics(mostServicedItems.value, currentTabIndex.value).length ?
    getServiceDataFromStatistics(mostServicedItems.value, currentTabIndex.value).map((d) => d.serviceName) : [],
  responsive: [{
    breakpoint: 480,
    options: {
      chart: {
        width: 200
      },
      legend: {
        show: false
      }
    }
  }],
  legend: {
    position: 'bottom',
    offsetY: 0,
    height: 30,
  },
  title: {
    text: t('analytics.chart.mostRequestedServices'),
    align: 'right',
    style: {
      fontSize: '16px',
      fontWeight: 'normal',
      fontFamily: 'Readex Pro',
      color: '#0D0D0D'
    }
  },
});

watchEffect(() => {
  optionsRef.value = {
    ...optionsRef.value,
    title: {
      ...optionsRef.value.title,
      text: t('analytics.chart.mostRequestedServices')
    }
  }
})

const seriesRef = ref(
  getServiceDataFromStatistics(mostServicedItems.value, currentTabIndex.value).length ?
    getServiceDataFromStatistics(mostServicedItems.value, currentTabIndex.value).map((d) => d.count) : []
);

watch(mostServicedItems, async (newItems, oldItems) => {
  console.log(newItems);
  optionsRef.value = {
    ...optionsRef.value,
    labels: getServiceDataFromStatistics(newItems, currentTabIndex.value).length ?
      getServiceDataFromStatistics(newItems, currentTabIndex.value).map((d) => d.serviceName) : [],
  };
  console.log(getServiceDataFromStatistics(newItems, currentTabIndex.value).map((d) => d.serviceName));
  seriesRef.value = getServiceDataFromStatistics(newItems, currentTabIndex.value).map((d) => d.count);
});

</script>

<template>
  <div>
    <apexchart type="donut" :options="optionsRef" :series="seriesRef" class="mx-auto"></apexchart>
  </div>
</template>

<style lang="scss" scoped></style>