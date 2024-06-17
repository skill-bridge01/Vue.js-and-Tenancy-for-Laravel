<script setup>
import { ref, watch, watchEffect } from 'vue';
import { useI18n } from 'vue-i18n'
import { storeToRefs } from "pinia";
import { useStatisticStore } from '../../../store/pinia/statistic';
import { getDataLabelByQuery } from '../../../utils'
const { mostServices, loading, currentTabIndex } = storeToRefs(useStatisticStore());

const { t } = useI18n();

const seriesRef = ref([]);
const optionsRef = ref({
  colors: ['#4BC49A', '#3699FF', '#F53488'],
  chart: {
    type: 'line',
    height: 'auto',
    width: '100%',
    zoom: {
      enabled: false
    },
    toolbar: {
      show: false
    },
    foreColor: '#373d3f'
  },
  dataLabels: {
    enabled: false
  },
  stroke: {
    curve: 'straight'
  },
  // fill: {
  //   colors: ['#4BC49A'],
  //   type: "gradient",
  //   gradient: {
  //     shadeIntensity: 1,
  //     opacityFrom: 0.7,
  //     opacityTo: 0.9,
  //     stops: [0, 90, 100]
  //   }
  // },

  title: {
    text: t('analytics.chart.bestSellingServices'),//'الخدمات الأكثر مبيعاً',
    align: 'right',
    style: {
      fontSize: '16px',
      fontWeight: 'normal',
      fontFamily: 'Readex Pro',
      color: '#0D0D0D'
    }
  },
  subtitle: {
    text: '',
    align: 'right',
    style: {
      fontSize: '36px',
      fontWeight: 'normal',
      fontFamily: 'Readex Pro',
      color: '#484860'
    }
  },
  xaxis: {
    categories: getDataLabelByQuery(currentTabIndex.value),
  },
  yaxis: {
    opposite: true
  },
  legend: {
    horizontalAlign: 'left'
  }
});

watchEffect(() => {
  optionsRef.value = {
    ...optionsRef.value,
    title: {
      ...optionsRef.value.title,
      text: t('analytics.chart.bestSellingServices')
    }
  }
})

watch(currentTabIndex, async (newIndex, oldIndex) => {
  optionsRef.value = {
    ...optionsRef.value,
    ...{
      xaxis: {
        categories: getDataLabelByQuery(newIndex)
      }
    }
  };
});

watch(mostServices, async (newServices, oldServices) => {
  let dataArr = newServices.map((s, index) => ({ name: `${s['service_name']}`, data: s['sub_total'] }));
  seriesRef.value = [
    ...dataArr
  ];
});

</script>

<template>
  <div>
    <apexchart type="line" :options="optionsRef" :series="seriesRef" class="mx-auto"></apexchart>
  </div>
</template>

<style lang="scss" scoped></style>