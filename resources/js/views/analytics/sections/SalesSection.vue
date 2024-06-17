<script setup>
import SaleChart from '../components/SaleChart.vue';
import SellingServiceChart from '../components/SellingServiceChart.vue';

</script>
<template>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <SellingServiceChart class="p-4 rounded-md shadow-md bg-white" />
    <SaleChart class="p-4 rounded-md shadow-md bg-white" />
  </div>
</template>