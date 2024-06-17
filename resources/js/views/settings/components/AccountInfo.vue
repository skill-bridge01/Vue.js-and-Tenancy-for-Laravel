<script setup>
import BaseCheckbox from '../../../components/BaseCheckbox.vue';
import BaseIcon from '../../../components/BaseIcon.vue';
</script>
<template>
  <div class="flex flex-col gap-6">
    <div class="flex justify-between items-center">
      <div></div>
      <div></div>
    </div>
    <div class="text-right">
      <label for="user_name">User Name</label>
      <div class="border rounded-lg border-[#ECF1F4] pl-6 pr-4 py-2 bg-white mt-3">
        <input
          id="user_name"
          type="text"
          name="user_name"
          placeholder="David John"
          class="
            text-right
            font-readex
            text-base
            border-none
            outline-none
            focus:outline-none
          "
        />
      </div>
    </div>
    <div class="text-right flex flex-col items-end">
      <div class="w-full flex items-center gap-2">
        <hr class="w-8/12" />
        <span for="slider">Section 1</span>
      </div>
      <BaseCheckbox />
      <BaseCheckbox />
      <BaseCheckbox />
    </div>
    <div class="text-right flex flex-col items-end">
      <div class="w-full flex items-center gap-2">
        <hr class="w-8/12" />
        <p for="slider">Section 2</p>
      </div>
      <BaseCheckbox />
      <BaseCheckbox />
      <BaseCheckbox />
    </div>
  </div>
</template>