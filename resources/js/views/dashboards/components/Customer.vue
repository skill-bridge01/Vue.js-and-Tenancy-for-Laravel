<script setup>
</script>
<template>
  <div class="flex justify-between items-center px-6 py-3 customer-wrapper">
    <button type="button" @click="modalOneActive = true">
      <img class="" :src="`/images/home/plus.svg`" alt="" />
    </button>
    <div class="flex justify-between items-center">
      <span class="customer-label">الزبون</span>
      <button type="button">
        <img class="" :src="`/images/home/placeholder_user.svg`" alt="" />
      </button>

    </div>
  </div>
</template>
<style lang="scss" scoped>
.customer-wrapper {
  border-bottom: 1px solid #ECF1F4;
}
</style>