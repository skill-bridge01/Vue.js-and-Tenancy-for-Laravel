<script setup>
import { ref } from "vue";
import { useI18n } from "vue-i18n";
import CompanyLogoIcon from "@/assets/icons/company_logo.svg";
import BaseInput from "../../components/BaseInput.vue";
import { Form, useForm } from "vee-validate";
import * as Yup from "yup";
import { useAuthStore } from "@/store/pinia/auth";
import { authService } from "../../services";
import router from "../../router";
import ThreeDotAnimator from "@/components/ThreeDotAnimator.vue";
import { HollowDotsSpinner } from "epic-spinners";
import VOtpInput from "vue3-otp-input";
import {
    NotificationProvider,
    dispatchNotification,
} from "../../components/Notification";

const { t } = useI18n();

const showAnimator = ref(false);
const errorMessage = ref(null);
const otp = ref("");
const otpInput = ref(null);
const bindModal = ref("");
const bindValue = ref("");

const handleOnComplete = (value) => {
    console.log("OTP completed: ", value);
    bindValue.value = value;
};

const handleOnChange = (value) => {
    console.log("OTP changed: ", value);
};

const clearInput = () => {
    otpInput.value?.clearInput();
};

const fillInput = (value) => {
    console.log(value);
    otpInput.value?.fillInput(value);
};

const authStore = useAuthStore();
// Using yup to generate a validation schema
// https://vee-validate.logaretm.com/v4/guide/validation#validation-schemas-with-yup
const schema = Yup.object().shape({
    // email: Yup.string().email().required(),
    phone: Yup.string().min(6).required(),
    // confirm_password: Yup.string()
    //   .required()
    //   .oneOf([Yup.ref('password')], 'Passwords do not match'),
});

const handleSubmit = (pin) => {
    otp.value = pin;
    // Handle API request here, but for demonstration, we're console logging it
    console.log("pin", pin);
};

const onSubmit = (values) => {
    errorMessage.value = null;
    // Submit to API
    console.log(
        "Verify code",
        values,
        "otpInput.value",
        otpInput.value,
        "bindValue",
        bindValue.value
    ); // { email: 'email@gmail.com' }
    showAnimator.value = true;
    authService
        .phoneVerify2(
            bindValue.value
            // device_name: "iphone",
            //{ ...values,}
        )
        .then((res) => {
            if (res.data.success) {
                dispatchNotification({
                    title: "Success!",
                    content: "Phone Verify Success.",
                    type: "success",
                });
                setTimeout(() => {
                    router.push("/login");
                }, 1500);
            } else {
                errorMessage.value = "Please input correct code";
                showAnimator.value = false;
            }

            // console.log("res", res);
            // router.push("/login");
        })
        .catch((err) => {
            console.log("Signin Error:", err);
            if (err.response.status === 404) {
                console.log("phoneVerify error");
                errorMessage.value = "Please input correct code";
            } else {
                errorMessage.value = err.response?.data?.message;
            }
            showAnimator.value = false;
        })
        .finally(() => {});
};
</script>

<template>
    <div
        class="auth-layout-wrap flex justify-center min-h-screen flex-col bg-cover items-center h-screen"
    >
        <div class="overlayer flex justify-center items-center">
            <NotificationProvider>
                <div class="container-session-v1 max-w-2xl w-[480px]">
                    <BaseCard noPadding class="overflow-hidden px-9 py-12">
                        <div class="grid grid-cols-12">
                            <div class="col-span-12">
                                <Form
                                    class="flex flex-col gap-9"
                                    @submit="onSubmit"
                                >
                                    <div
                                        class="flex items-center justify-center"
                                    >
                                        <CompanyLogoIcon />
                                        <h1 class="logo-text ml-2">
                                            {{ t("common.appName") }}
                                        </h1>
                                    </div>

                                    <div>
                                        <!-- <base-input
                                            input-type="text"
                                            name="phone"
                                            :icon-src="`/images/home/phone.svg`"
                                            :placeholder="
                                                t('auth.phoneVerify.phone')
                                            "
                                            class="w-full mb-6 font-readex text-base font-light"
                                        /> -->
                                        <p
                                            class="text-sm ml-5 mb-3"
                                        >
                                            {{ t("auth.phoneVerify.phone") }}
                                        </p>
                                        <!-- <otp :digit-count="6" /> -->
                                        <div
                                            class="flex flex-row justify-center"
                                        >
                                            <v-otp-input
                                                ref="otpInput"
                                                name="phone"
                                                input-classes="otp-input"
                                                :conditionalClass="[
                                                    'one',
                                                    'two',
                                                    'three',
                                                    'four',
                                                ]"
                                                separator="-"
                                                inputType="letter-numeric"
                                                :num-inputs="6"
                                                v-model:value="bindValue"
                                                :should-auto-focus="true"
                                                :should-focus-order="true"
                                                @on-change="handleOnChange"
                                                @on-complete="handleOnComplete"
                                            />
                                        </div>
                                    </div>
                                    <div v-if="errorMessage">
                                        <p class="text-red-500 text-sm">
                                            {{ errorMessage }}
                                        </p>
                                    </div>
                                    <div v-if="!showAnimator">
                                        <!-- <router-link to="/dashboards" -->
                                        <button
                                            type="submit"
                                            class="flex px-6 py-3 justify-center items-center bg-main w-full rounded-lg mb-3 cursor-pointer"
                                        >
                                            <span
                                                class="font-readex text-base text-white"
                                            >
                                                {{
                                                    t(
                                                        "auth.phoneVerify.confirm"
                                                    )
                                                }}
                                            </span>
                                        </button>
                                        <!-- </router-link> -->
                                        <!-- <router-link
                                        to="/register"
                                        class="flex px-6 py-3 justify-center items-center border border-[#ECF1F4] w-full rounded-lg mb-3 cursor-pointer"
                                    >
                                        <span
                                            class="font-readex text-base text-[#737F98]"
                                        >
                                            {{ t("auth.login.register") }}
                                        </span>
                                    </router-link> -->
                                    </div>
                                    <div class="flex justify-center" v-else>
                                        <hollow-dots-spinner
                                            :animation-duration="1000"
                                            :dot-size="15"
                                            :dots-num="3"
                                            color="#4BC49A"
                                        />
                                    </div>

                                    <!-- <div class="text-center">
                  <a class="hover:text-purple-500 text-gray-600 mr-6 font-readex text-base" href="#">English</a>
                  <a class="hover:text-purple-500 text-black font-readex text-base" href="#">Arabic</a>
                </div> -->
                                </Form>
                            </div>
                        </div>
                    </BaseCard>
                </div>
            </NotificationProvider>
        </div>
    </div>
</template>
<style lang="scss" scoped>
.auth-layout-wrap {
    // background-image: url(../../images/bg_white_shirt.png);
    background: url("../../images/bg_white_shirt.png"),
        lightgray 50% / cover no-repeat;

    background-blend-mode: luminosity;

    .overlayer {
        width: 100%;
        height: 100%;
        background: rgba(69, 169, 203, 0.3);
        backdrop-filter: blur(10px);

        .logo-text {
            color: #484860;
            text-align: center;
            font-size: 20px;
            font-family: "Readex Pro";
            font-weight: 300;
            text-transform: uppercase;
        }
    }
}
</style>
