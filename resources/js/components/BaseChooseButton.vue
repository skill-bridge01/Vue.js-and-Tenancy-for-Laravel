<script setup>
import { computed } from "vue";
import { useI18n } from "vue-i18n";
const { t } = useI18n();
const props = defineProps({
  for: {
    type: String,
    default: null,
  },
})
</script>
<template>
    <label
        :for="props.for"
        class="cursor-pointer flex px-4 py-2 justify-center items-center bg-green-400 w-fit rounded-[4px]"
    >
        {{ t("common.chooseImage") }}
    </label>
</template>
