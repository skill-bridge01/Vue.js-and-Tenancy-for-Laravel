<script setup>
import { computed, ref, onBeforeMount, onUnmounted, watchEffect, watch } from "vue";
import { useI18n } from 'vue-i18n'
import BaseIcon from '../../../components/BaseIcon.vue';
import BaseInput from "../../../components/BaseInput.vue";
import { mdiCheck } from '@mdi/js'
import { useAuthStore } from "@/store/pinia/auth";
import { authService } from "../../../services";
import { storeToRefs } from "pinia";
import { Form, useForm } from 'vee-validate';
import * as Yup from 'yup';

const schema = Yup.object().shape({
  old_password: Yup.string().min(8).required(),
  new_password: Yup.string().min(8).required(),
  confirm_password: Yup.string()
    .required()
    .oneOf([Yup.ref('new_password')], 'Passwords do not match'),
});
const authStore = useAuthStore()
const { t } = useI18n();
const { user } = storeToRefs(useAuthStore());
const errorMessage = ref(null)
const successMessage = ref(null)



onBeforeMount(() => {
  // invoicesStore.fetchLocalData()
  authStore.fetchUser()
})

const onSubmit = (values, actions) => {
  console.log(actions)
  errorMessage.value = null
  // Submit to API
  console.log(values); // { email: 'email@gmail.com' }
  authService.updatePassword(values).then(res => {
    console.log(res)
    if (res.error) {
      errorMessage.value = res.error;
    } else if (res.success) {
      successMessage.value = res.message;
      actions.resetForm()
      setTimeout(() => {
        successMessage.value = null
      }, 3000)
    }
  }).catch((err) => {
    console.log(err)
  })
}

</script>
<template>
  <Form @submit="onSubmit" :validation-schema="schema" v-slot="{ setFieldValue, setValues }">
    <div class="flex flex-wrap gap-14 pt-9">
      <div class="flex flex-col gap-6">
        <div class="text-right">
          <label for="old_password" class="font-readex text-xs font-medium">
            {{ t('account.oldPassword') }}
          </label>
          <base-input input-type="password" name="old_password" :icon-src="`/images/login/password_grey.svg`"
            :placeholder="t('account.oldPassword')" class="w-full font-readex text-base font-light mb-6" />
        </div>
        <div class="text-right">
          <label for="new_password" class="font-readex text-sm font-medium">
            {{ t('account.newPassword') }}
          </label>
          <base-input input-type="password" name="new_password" :icon-src="`/images/login/password_grey.svg`"
            :placeholder="t('account.newPassword')" class="w-full font-readex text-base font-light mb-6" />
        </div>
        <div class="text-right">
          <label for="confirm_password" class="font-readex text-sm font-medium">
            {{ t('account.confirmPassword') }}
          </label>
          <base-input input-type="password" name="confirm_password" :icon-src="`/images/login/password_grey.svg`"
            :placeholder="t('account.confirmPassword')" class="w-full font-readex text-base font-light mb-6" />
        </div>
        <div v-if="errorMessage">
          <p class="text-red-500 text-sm">{{ errorMessage }}</p>
        </div>
        <div v-if="successMessage">
          <p class="text-green-400 text-sm">{{ successMessage }}</p>
        </div>
      </div>
      <div class="flex flex-col gap-6">
        <div class="text-right">
          <label for="email" class="font-readex text-sm font-medium">
            {{ t('account.email') }}
          </label>
          <div class="flex flex-col border rounded-lg border-[#ECF1F4] pl-6 pr-4 py-2 bg-white mt-3">
            <input id="email" type="email" name="email" :placeholder="t('account.email')" class="
              text-right
              font-readex
              text-base
              border-none
              outline-none
              focus:outline-none
            " disabled :value="user?.email" />
          </div>
        </div>
        <div class="text-right">
          <label for="phone" class="font-readex text-sm font-medium">
            {{ t('account.mobile') }}
          </label>
          <div class="flex flex-col border rounded-lg border-[#ECF1F4] pl-6 pr-4 py-2 bg-white mt-3">
            <input id="phone" type="text" name="phone" :placeholder="t('account.mobile')" class="
              text-right
              font-readex
              text-base
              border-none
              outline-none
              focus:outline-none
            " disabled :value="user?.phone" />
          </div>
        </div>
      </div>
    </div>
    <div class="w-full mt-16">
      <button type="submit" class="flex px-6 py-3 justify-center items-center bg-brandGreen w-fit rounded-lg">
        <span class="font-readex text-sm text-white">
          Save
        </span>
        <BaseIcon :path="mdiCheck" class="ml-3 text-white" />
      </button>
    </div>
  </Form>

</template>