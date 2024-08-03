<script setup>
import { ref, computed, watchEffect } from "vue";
import { useI18n } from "vue-i18n";

import CompanyLogoIcon from "@/assets/icons/company_logo.svg";
import BaseInput from "../../components/BaseInput.vue";

import { Form, useForm } from "vee-validate";
import * as Yup from "yup";
import { useAuthStore } from "@/store/pinia/auth";
import { authService } from "../../services";
import { useRoute, useRouter } from "vue-router";
import router from "../../router";
import ThreeDotAnimator from "@/components/ThreeDotAnimator.vue";
import { HollowDotsSpinner } from "epic-spinners";
import {
    NotificationProvider,
    dispatchNotification,
} from "../../components/Notification";

const { t, locale } = useI18n();
const LOCALES = [
    {
        label: "EN",
        value: "en",
    },
    {
        label: "AR",
        value: "ar",
    },
];
const selectedLocale = ref(locale.value);

watchEffect(() => {
    locale.value = LOCALES.find((l) => l.value === selectedLocale.value).value;
});

const showAnimator = ref(false);
const errorMessage = ref(null);
const originURL = ref("");
const username = ref("");

const authStore = useAuthStore();
// Using yup to generate a validation schema
// https://vee-validate.logaretm.com/v4/guide/validation#validation-schemas-with-yup
const schema1 = Yup.object().shape({
    username: Yup.string().min(6).required(),
    name: Yup.string().min(6).required(),
    phone: Yup.string().required(),
    email: Yup.string().email().required(),
    password: Yup.string().min(8).required(),
    password_confirmation: Yup.string()
        .required()
        .oneOf([Yup.ref("password")], "Passwords do not match"),
});

const schema = computed(() => {
    const { t } = useI18n();
    return Yup.object().shape({
        email: Yup.string()
            .email(t("validation.email"))
            .matches(/^.+@.+\..+$/, t("validation.email"))
            .required(t("validation.emailRequired")),
        username: Yup.string()
            .min(6, t("validation.username.min"))
            .required(t("validation.username.required")),
        name: Yup.string()
            .min(6, t("validation.name.min"))
            .required(t("validation.name.required")),
        phone: Yup.string().required(t("validation.phone.required")),
        password: Yup.string()
            .min(8, t("validation.password.min"))
            .required(t("validation.password.required")),
        password_confirmation: Yup.string()
            .required(t("validation.password_confirmation.required"))
            .oneOf(
                [Yup.ref("password")],
                t("validation.password_confirmation.notMatch")
            ),
    });
});

const onSubmit = (values) => {
    console.log("originURL1", window.location.host);
    originURL.value = window.location.host;
    username.value = values.username;
    console.log("username", values.username, values);
    // Submit to API
    console.log(values); // { email: 'email@gmail.com' }
    errorMessage.value = null;
    showAnimator.value = true;
    authService
        .signup({
            ...values,
        })
        .then((res) => {
            console.log(res);
            if (res.status) {
                dispatchNotification({
                    title: "Success!",
                    content: "Please verify your email.",
                    type: "success",
                });
                setTimeout(() => {
                    // router.push("/phoneVerify");

                    window.location.replace(
                        "http://" +
                            username.value +
                            "." +
                            originURL.value +
                            "/phoneVerify"
                    );
                }, 1500);
            }
        })
        .catch((err) => {
            showAnimator.value = false;
            console.log(err);
            // errorMessage.value = err.response?.data?.message;
            errorMessage.value = t("auth.login.error");
            setTimeout(() => {
                errorMessage.value = null;
            }, 1500);
        });
};
</script>

<template>
    <div class="auth-layout-wrap bg-cover fixed w-screen h-screen">
        <div class="overlayer fixed w-screen h-screen overflow-scroll">
            <div class="flex w-full justify-end pr-24 pt-3">
                <select
                    v-model="selectedLocale"
                    class="border-none outline-none focus:outline-none"
                >
                    <option v-for="l in LOCALES" :value="l.value">
                        {{ l.label }}
                    </option>
                </select>
            </div>
            <div class="flex justify-center items-center w-full h-full">
                <NotificationProvider>
                    <div
                        class="container-session-v1 max-w-2xl w-[480px] mx-auto my-auto"
                    >
                        <BaseCard noPadding class="overflow-hidden px-9 py-12">
                            <div class="grid grid-cols-12">
                                <div class="col-span-12">
                                    <Form
                                        class="flex flex-col gap-5"
                                        @submit="onSubmit"
                                        :validation-schema="schema"
                                    >
                                        <div
                                            class="flex items-center justify-center"
                                        >
                                            <CompanyLogoIcon />
                                            <h1 class="logo-text ml-2"></h1>
                                        </div>
                                        <div>
                                            <base-input
                                                input-type="email"
                                                name="email"
                                                :icon-src="`/images/login/email_grey.svg`"
                                                :placeholder="
                                                    t('auth.login.email')
                                                "
                                                class="w-full mb-3 font-readex text-base font-light"
                                            />
                                            <base-input
                                                input-type="text"
                                                name="name"
                                                :icon-src="`/images/home/placeholder_user.svg`"
                                                :placeholder="
                                                    t('auth.signup.name')
                                                "
                                                class="w-full mb-3 font-readex text-base font-light"
                                            />
                                            <base-input
                                                input-type="text"
                                                name="username"
                                                :icon-src="`/images/home/placeholder_user.svg`"
                                                :placeholder="
                                                    t('auth.signup.username')
                                                "
                                                class="w-full mb-3 font-readex text-base font-light"
                                            />
                                            <base-input
                                                input-type="tel"
                                                name="phone"
                                                :icon-src="`/images/home/phone.svg`"
                                                :placeholder="
                                                    t('auth.signup.phoneNumber')
                                                "
                                                class="w-full mb-3 font-readex text-base font-light"
                                            />
                                            <base-input
                                                input-type="password"
                                                name="password"
                                                :icon-src="`/images/login/password_grey.svg`"
                                                :placeholder="
                                                    t('auth.login.password')
                                                "
                                                class="w-full font-readex text-base font-light mb-3"
                                            />
                                            <base-input
                                                input-type="password"
                                                name="password_confirmation"
                                                :icon-src="`/images/login/password_grey.svg`"
                                                :placeholder="
                                                    t(
                                                        'auth.signup.confirmPassword'
                                                    )
                                                "
                                                class="w-full font-readex text-base font-light"
                                            />
                                        </div>
                                        <div v-if="errorMessage">
                                            <p class="text-red-500 text-sm text-right">
                                                {{ errorMessage }}
                                            </p>
                                        </div>

                                        <div v-if="!showAnimator">
                                            <button
                                                type="submit"
                                                class="flex px-6 py-3 justify-center items-center bg-main w-full rounded-lg mb-3 cursor-pointer"
                                            >
                                                <span
                                                    class="font-readex text-base text-white"
                                                >
                                                    {{
                                                        t(
                                                            "auth.signup.continue"
                                                        )
                                                    }}
                                                </span>
                                            </button>
                                            <router-link
                                                class="flex px-6 py-3 justify-center items-center border border-[#ECF1F4] w-full rounded-lg cursor-pointer"
                                                to="/login"
                                            >
                                                <span
                                                    class="font-readex text-base text-[#737F98]"
                                                >
                                                    {{ t("auth.login.signIn") }}
                                                </span>
                                            </router-link>
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
                                                <a class="hover:text-purple-500 text-gray-600 mr-6 font-readex text-base"
                                                    href="#">English</a>
                                                <a class="hover:text-purple-500 text-black font-readex text-base"
                                                    href="#">Arabic</a>
                                            </div> -->
                                    </Form>
                                </div>
                            </div>
                        </BaseCard>
                    </div>
                </NotificationProvider>
            </div>
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
