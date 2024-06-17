<script setup>
const props = defineProps({
  customer: Object
});
const emits = defineEmits([
  'handleRemoveCustomer',
  'handleEditCustomer'
])

const handleClickTrash = () => {
  emits('handleRemoveCustomer', props.customer)
}

const handleClickEdit = () => {
  emits('handleEditCustomer', props.customer)
}

</script>
<template>
  <div class="flex justify-between items-center px-6 py-3 customer-wrapper">
    <div class="flex items-center gap-4">
      <button type="button" @click="handleClickTrash">
        <img class="" :src="`/images/home/trash_red.svg`" alt="" />
      </button>
      <button type="button" @click="handleClickEdit">
        <img class="" :src="`/images/home/edit_grey.svg`" alt="" />
      </button>
    </div>
    <div>
      <div class="flex justify-end items-center">
        <span class="name-label font-readex font-normal text-[16px]">{{ customer?.name }}</span>
        <button type="button">
          <img class="" :src="`/images/home/placeholder_user.svg`" alt="" />
        </button>
      </div>
      <span class="name-label font-readex font-light text-[16px]">{{ customer?.mobile }}</span>
    </div>
  </div>
</template>
<style lang="scss" scoped>
.customer-wrapper {
  border-bottom: 1px solid #ECF1F4;
}
</style>