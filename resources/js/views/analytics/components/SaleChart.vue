<script setup>
import { ref, watch, watchEffect } from "vue";
import { useI18n } from 'vue-i18n'
import { storeToRefs } from "pinia";
import { useStatisticStore } from '../../../store/pinia/statistic';
import { getDataLabelByQuery } from '../../../utils'

const { t } = useI18n();

const { yearData, monthData, dayData, totalSales, loading, currentTabIndex } = storeToRefs(useStatisticStore());

const optionsRef = ref({
  colors: ['#4BC49A'],
  chart: {
    type: 'area',
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
  fill: {
    colors: ['#4BC49A'],
    type: "gradient",
    gradient: {
      shadeIntensity: 1,
      opacityFrom: 0.7,
      opacityTo: 0.9,
      stops: [0, 90, 100]
    }
  },

  title: {
    text: t('analytics.chart.sales'),
    align: 'right',
    style: {
      fontSize: '16px',
      fontWeight: 'normal',
      fontFamily: 'Readex Pro',
      color: '#0D0D0D'
    }
  },
  subtitle: {
    text: '0',
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

const seriesRef = ref(
  [{
    name: 'Vue Chart',
    data: [30, 40, 45, 50, 49, 60, 70, 81, 98, 78, 96]
  }]
);

watchEffect(() => {
  optionsRef.value = {
    ...optionsRef.value,
    title: {
      ...optionsRef.value.title,
      text: t('analytics.chart.sales')
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

watch(totalSales, async (newTotalSales, oldTotalSales) => {
  seriesRef.value = [
    {
      name: 'Total Sales',
      data: newTotalSales
    }
  ];
  
  const totalValue = newTotalSales.reduce((partialSum, a) => partialSum + a, 0);
  optionsRef.value = {
    ...optionsRef.value,
    ...{
      subtitle: {
        text: `${totalValue}`,
        align: 'right',
        style: {
          fontSize: '36px',
          fontWeight: 'normal',
          fontFamily: 'Readex Pro',
          color: '#484860'
        }
      }
    }
  };
});

</script>

<template>
  <div>
    <apexchart type="area" :options="optionsRef" :series="seriesRef" class="mx-auto"></apexchart>
  </div>
</template>

<style lang="scss" scoped></style>
