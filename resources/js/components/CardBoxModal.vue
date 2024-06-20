<script setup>
import { computed, onMounted } from "vue";
import { useI18n } from 'vue-i18n'
import { mdiClose } from "@mdi/js";
import BaseButton from "@/components/BaseButton.vue";
import BaseButtons from "@/components/BaseButtons.vue";
import CardBox from "@/components/CardBox.vue";
import OverlayLayer from "@/components/OverlayLayer.vue";
import CardBoxComponentTitle from "@/components/CardBoxComponentTitle.vue";

const { t } = useI18n()

const props = defineProps({
  title: {
    type: String,
    required: true,
  },
  button: {
    type: String,
    default: "info",
  },
  buttonLabel: {
    type: String,
    default: "Done",
  },
  hasCancel: Boolean,
  modelValue: {
    type: [String, Number, Boolean],
    default: null,
  },
  showModal: {
    type: Boolean,
    default: false
  },
  hasButtons: {
    type: Boolean,
    default: true
  },
  justifyType: {
    type: String,
    default: 'justify-start'
  }
});

const emit = defineEmits(["update:modelValue", "cancel", "confirm"]);

const value = computed({
  get: () => props.modelValue,
  set: (value) => emit("update:modelValue", value),
});

const confirmCancel = (mode) => {
  // if(hasButtons==true){
  //   value.value = true;
  // } else{
    value.value = false;
  // }
  emit(mode);
};

const confirmButton = (mode) => {
  // value.value = false;
  emit(mode);
};

const confirm = () => confirmCancel("confirm");
const confirmShowModal = () => confirmButton("confirm");

const cancel = () => confirmCancel("cancel");

window.addEventListener("keydown", (e) => {
  if (e.key === "Escape" && value.value) {
    cancel();
  }
});
</script>

<template>
  <OverlayLayer v-show="value" @overlay-click="cancel">
    <CardBox
      v-show="value"
      class="shadow-lg max-h-modal w-11/12 md:w-3/5 lg:w-2/5 xl:w-4/12 z-50"
      :class="value ? '' : 'hidden'"
      is-modal
    >
      <CardBoxComponentTitle :title="title">
        <!-- <BaseButton
          v-if="hasCancel"
          :icon="mdiClose"
          color="whiteDark"
          small
          rounded-full
          @click.prevent="cancel"
        /> -->
      </CardBoxComponentTitle>

      <div class="space-y-3">
        <slot />
      </div>

      <template #footer v-if="hasButtons">
        <BaseButtons :type="props.justifyType">
          <BaseButton v-if="showModal" :label="buttonLabel" :color="button" @click="confirmShowModal" />
          <BaseButton v-else :label="buttonLabel" :color="button" @click="confirm" />
          <BaseButton
            v-if="hasCancel"
            :label="t('modal.logout.back')"
            :color="button"
            outline
            @click="cancel"
          />
        </BaseButtons>
      </template>
    </CardBox>
  </OverlayLayer>
</template>
