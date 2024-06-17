<script setup>
import { ref, watch, watchEffect } from 'vue';
import { useI18n } from 'vue-i18n'
import { storeToRefs } from "pinia";
import { useStatisticStore } from '../../../store/pinia/statistic';
import DemandService from '../components/DemandService.vue';
import StatisticTable from '../components/StatisticTable.vue';
import { getTableOptionsForCustomers } from '../../../utils';

const { t } = useI18n();

const { mostPurchasedCustomerArr, loading, currentTabIndex } = storeToRefs(useStatisticStore());

const customerTableOptions = ref({
  headerLabels: [t('analytics.chart.totalBills'), t('analytics.chart.customer')],
  data: getTableOptionsForCustomers(mostPurchasedCustomerArr.value)
})
const invoiceTableOptions = {
  headerLabels: [t('analytics.chart.value'), t('analytics.chart.bill')],
  data: [
    {amount: 123, invoiceNumber: 'INV-23-16574'},
    {amount: 85, invoiceNumber: 'INV-23-16574'},
    {amount: 5, invoiceNumber: 'INV-23-16574'},
    {amount: 1, invoiceNumber: 'INV-23-16574'},
    {amount: 5, invoiceNumber: 'INV-23-16574'},
  ]
}

watchEffect(() => {
  invoiceTableOptions.headerLabels = [t('analytics.chart.value'), t('analytics.chart.bill')]
  customerTableOptions.value.headerLabels = [t('analytics.chart.totalBills'), t('analytics.chart.customer')]
})

watch(mostPurchasedCustomerArr, async (newCustomerArr, oldCustomerArr) => {
  console.log(newCustomerArr);
  customerTableOptions.value = {
    ...customerTableOptions.value,
    data: getTableOptionsForCustomers(newCustomerArr)
  }
});
</script>
<template>
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <StatisticTable
      data-type="customers"
      :title="t('analytics.chart.mostPurchasedCustomers')"
      :table-options="customerTableOptions"
    />
    <DemandService class="p-4 rounded-md shadow-md bg-white" />
    <StatisticTable
      data-type="invoices"
      :title="t('analytics.chart.mostPurchasedCustomers')"
      :table-options="invoiceTableOptions"
    />
  </div>
</template>