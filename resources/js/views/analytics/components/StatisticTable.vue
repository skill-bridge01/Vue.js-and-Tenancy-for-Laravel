<script setup>
import { ref, watch } from 'vue';
import { useI18n } from 'vue-i18n'
import { storeToRefs } from "pinia";
import { useStatisticStore } from '../../../store/pinia/statistic';

const { t } = useI18n();

const { mostPurchasedCustomerArr, loading, currentTabIndex } = storeToRefs(useStatisticStore());

const props = defineProps({
  title: String,
  tableOptions: {
    type: Object,
    default: {
      headerLabels: [],
      data: []
    }
    
  },
  dataType: {
    type: String,
    default: 'invoices'
  }
});


</script>
<template>
  <div class="p-4 rounded-md shadow-md bg-white">
    <div 
      class="flex items-center  mb-4"
      :class="dataType === 'invoices' ? 'justify-between' : 'justify-end'"
    >
      <button v-if="dataType === 'invoices'" type="button" class="underline text-main">
        {{ t('analytics.chart.viewAll') }}
      </button>
      <p class="font-readex text-base text-[#0D0D0D]">
      {{ title }}
      </p>
    </div>
    <div class="rounded-2xl border border-solid border-[#E2E8F0]">
      <table>
      <thead>
        <tr>
          <th v-for="label in props.tableOptions.headerLabels" :key="label">{{label}}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(row, index) in props.tableOptions.data" :key="index">
          <td data-label="amount">
            {{ (props.dataType === 'invoices' ? row.amount : row.amount) + ' ر.س' }} 
          </td>
          <td data-label="invoice">
            {{ props.dataType === 'invoices' ? row.invoiceNumber : row.name }}
          </td>
        </tr>
      </tbody>
    </table>
    </div>

  </div>
</template>
<style lang="scss" scoped></style>