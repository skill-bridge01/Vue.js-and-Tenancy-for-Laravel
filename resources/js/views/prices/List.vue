<script setup>
import { ref } from 'vue'
import CardBox from "@/components/CardBox.vue";
import SectionMain from "@/components/SectionMain.vue";
import TableDataClient from "./components/List/TableDataClient.vue";
import Header from "./components/List/Header.vue";

const isModalActive = ref(false);
const tableCompRef = ref(null);

const handleClickNewPiece = () => {
  console.log('new piece')
  isModalActive.value = true
  tableCompRef.value.showNewModal();
}
</script>
<template>
  <div>
    <section-main>
      <Header @handle-click-new-piece="handleClickNewPiece"/>
      <CardBox class="mb-6 mt-6" has-table>
        <table-data-client ref="tableCompRef"/>
      </CardBox>
    </section-main>
  </div>
</template>