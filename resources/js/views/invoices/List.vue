<script setup>
import CardBox from "@/components/CardBox.vue";
import TableDataClient from "@/components/TableDataClient.vue";
import SectionMain from "@/components/SectionMain.vue";
import SearchGroupHeader from "./components/SearchGroupHeader.vue";
</script>
<template>
  <div class="">
    <section-main>
      <SearchGroupHeader />
      <CardBox class="mb-6 mt-6" has-table>        
        <table-data-client />
      </CardBox>
    </section-main>
  </div>
</template>