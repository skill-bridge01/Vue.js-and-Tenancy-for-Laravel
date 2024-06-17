<script setup>
import BaseButton from '../../../components/BaseButton.vue';
import BaseIcon from '../../../components/BaseIcon.vue';
import BaseSelect from '../../../components/BaseSelect.vue';
import BaseSlider from '../../../components/BaseSlider.vue';
import AccountInfo from './AccountInfo.vue';
import { mdiCheck } from '@mdi/js'
</script>
<template>
  <div class="flex flex-wrap gap-14 pt-9">
    <div class="flex flex-col gap-6">
      <div class="text-right">
        <BaseSelect />
      </div>
      <div class="text-right">
        <BaseSelect />
      </div>
      <div class="text-right">
        <label for="slider">Option5</label>
        <BaseSlider id="slider" />
      </div>
      <div class="text-right">
        <label for="amount">Input6</label>
        <div class="border rounded-lg border-[#ECF1F4] pl-6 pr-4 py-2 bg-white mt-3">
          <input id="amount" type="text" name="amount" placeholder="Amount" class="
              text-right
              font-readex
              text-base
              border-none
              outline-none
              focus:outline-none
            " />
        </div>

      </div>
    </div>
    <AccountInfo />
  </div>
  <div class="w-full mt-16">
    <div class="flex px-6 py-3 justify-center items-center bg-brandGreen w-fit rounded-lg">
      <span class="font-readex text-sm text-white">
        Save
      </span>
      <BaseIcon :path="mdiCheck" class="ml-3 text-white"/>
    </div>
  </div>
</template>