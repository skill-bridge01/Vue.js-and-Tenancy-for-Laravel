<template>
  <div class="dropdown relative">
    <div class="cursor-pointer h-10 bg-white flex justify-end items-center py-3 pl-3" @click="show = !show">
      <span class="customer-label mr-2">{{ customer?.name }}</span>
      <div>
        <img class="" :src="`/images/home/placeholder_user.svg`" alt="" />
      </div>
    </div>
    <div
      class="absolute rounded shadow bg-white overflow-hidden peer-checked:flex flex-col w-[400%] right-0 mt-1 border border-gray-200 cursor-pointer"
      v-if="show"
      @click="show = !show"
      ref="target"
    >
      <slot />
    </div>
  </div>
</template>
<script>
import { ref } from "vue";
import { onClickOutside } from '@vueuse/core'
export default {
  props: ["customer"],
  setup(props) {
    const show = ref(false);
    const target = ref(null)
    onClickOutside(target, (event) => {
      show.value = !show.value
    })
    return {
      show,
      target
    };
  },
};
</script>

<style lang="scss" scoped>
.customer-label {
  font-family: 'Readex Pro';
  font-style: normal;
  font-weight: 300;
  font-size: 16px;
  line-height: 20px;
  /* identical to box height */

  text-align: center;

  /* Primary/DarkBlue-04 */

  color: #A1AABA;
}
</style>