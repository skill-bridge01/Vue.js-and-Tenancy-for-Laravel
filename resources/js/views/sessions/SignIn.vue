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

const { t } = useI18n();

const showAnimator = ref(false);
const errorMessage = ref(null);

const authStore = useAuthStore();
// Using yup to generate a validation schema
// https://vee-validate.logaretm.com/v4/guide/validation#validation-schemas-with-yup
const schema = Yup.object().shape({
    // email: Yup.string().email().required(),
    email: Yup.string().min(6).required(),
    password: Yup.string().min(8).required(),
    // confirm_password: Yup.string()
    //   .required()
    //   .oneOf([Yup.ref('password')], 'Passwords do not match'),
});

const onSubmit = (values) => {
    errorMessage.value = null;
    // Submit to API
    console.log(values); // { email: 'email@gmail.com' }
    showAnimator.value = true;
    authService
        .login({
            ...values,
            device_name: "iphone",
        })
        .then((res) => {
            console.log("res", res.token);
            authStore.setToken(res.token);
            router.push("/");
        })
        .catch((err) => {
            console.log("Signin Error:", err);
            if (err.response.status === 404) {
                console.log("ok");
                errorMessage.value = "Please input correct domain";
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
            <div class="container-session-v1 max-w-2xl w-[480px]">
                <BaseCard noPadding class="overflow-hidden px-9 py-12">
                    <div class="grid grid-cols-12">
                        <div class="col-span-12">
                            <Form
                                class="flex flex-col gap-9"
                                @submit="onSubmit"
                                :validation-schema="schema"
                            >
                                <div class="flex items-center justify-center">
                                    <CompanyLogoIcon />
                                    <h1 class="logo-text ml-2">

                                    </h1>
                                </div>

                                <div>
                                    <base-input
                                        input-type="email"
                                        name="email"
                                        :icon-src="`/images/login/email_grey.svg`"
                                        :placeholder="
                                            t('auth.login.email_username')
                                        "
                                        class="w-full mb-6 font-readex text-base font-light"
                                    />
                                    <base-input
                                        input-type="password"
                                        name="password"
                                        :icon-src="`/images/login/password_grey.svg`"
                                        :placeholder="t('auth.login.password')"
                                        class="w-full font-readex text-base font-light"
                                    />
                                </div>
                                <div v-if="errorMessage">
                                    <p class="text-red-500 text-sm ">
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
                                            {{ t("auth.login.signIn") }}
                                        </span>
                                    </button>
                                    <!-- </router-link> -->
                                    <router-link
                                        to="/register"
                                        class="flex px-6 py-3 justify-center items-center border border-[#ECF1F4] w-full rounded-lg mb-3 cursor-pointer"
                                    >
                                        <span
                                            class="font-readex text-base text-[#737F98]"
                                        >
                                            {{ t("auth.login.register") }}
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
                  <a class="hover:text-purple-500 text-gray-600 mr-6 font-readex text-base" href="#">English</a>
                  <a class="hover:text-purple-500 text-black font-readex text-base" href="#">Arabic</a>
                </div> -->
                            </Form>
                        </div>
                    </div>
                </BaseCard>
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
