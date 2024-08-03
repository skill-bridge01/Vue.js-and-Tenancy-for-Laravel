<script setup>
import { useI18n } from "vue-i18n";
import { ref, computed, watchEffect, onBeforeMount } from "vue";

const { t, locale } = useI18n();
</script>
<template>
    <section
        class="bg-white pt-6 lg:pt-12 pb-12 lg:pb-[90px] relative z-20 overflow-hidden"
        id="price"
    >
        <div class="container mx-auto">
            <div class="flex flex-wrap -mx-4">
                <div class="w-full px-4" data-aos="zoom-out">
                    <div
                        class="text-center mx-auto mb-[60px] lg:mb-20 max-w-[510px]"
                    >
                        <span
                            class="font-semibold text-lg text-primary mb-2 block"
                        >
                            {{ t("home.package.package") }}
                        </span>
                        <h2
                            class="md:text-[40px] text-dark mb-4 h-14 mt-4 max-w-sm bg-gradient-to-br from-gray-500 via-teal-500 to-gray-500 bg-clip-text text-center text-4xl font-extrabold text-transparent sm:max-w-4xl sm:text-6xl flex items-center justify-center"
                        >
                            {{ t("home.package.title") }}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap justify-center -mx-4">
                <div
                    data-aos="zoom-out-down"
                    class="w-full overflow-hidden md:w-1/2 lg:w-1/3 px-4 relative"
                >
                    <div
                        class="bg-white rounded-xl relative z-10 overflow-hidden shadow-xl border-primary border-opacity-20 shadow-pricing py-10 px-8 sm:p-12 lg:py-10 lg:px-6 xl:p-12 mb-10"
                    >
                        <span
                            class="text-primary font-semibold text-lg block mb-4"
                        >
                            {{ t("home.package.basicPlan") }}
                        </span>
                        <h2 class="font-bold mb-5 text-[42px] text-teal-600">
                            $59
                            <span class="text-base text-body-color font-medium">
                                / {{ t("home.package.year") }}
                            </span>
                        </h2>
                        <p
                            class="text-base text-body-color pb-8 mb-8 border-[#F2F2F2]"
                        >
                            {{ t("home.package.description") }}
                        </p>
                        <div class="mb-7">
                            <ul>
                                <li
                                    class="py-3 px-2 border-t hover:bg-teal-600 hover:text-white transition"
                                >
                                    {{ t("home.package.feature") }}
                                </li>
                                <li
                                    class="py-3 px-2 border-t hover:bg-teal-600 hover:text-white transition"
                                >
                                    {{ t("home.package.feature") }}
                                </li>
                                <li
                                    class="py-3 px-2 border-t hover:bg-teal-600 hover:text-white transition"
                                >
                                    {{ t("home.package.feature") }}
                                </li>
                                <li
                                    class="py-3 px-2 border-t hover:bg-teal-600 hover:text-white transition"
                                >
                                    {{ t("home.package.feature") }}
                                </li>
                                <li
                                    class="py-3 px-2 border-t hover:bg-teal-600 hover:text-white transition"
                                >
                                    {{ t("home.package.feature") }}
                                </li>
                                <li
                                    class="py-3 px-2 border-t hover:bg-teal-600 hover:text-white transition"
                                >
                                    {{ t("home.package.feature") }}
                                </li>
                                <li
                                    class="py-3 px-2 border-t hover:bg-teal-600 hover:text-white transition"
                                >
                                    {{ t("home.package.feature") }}
                                </li>
                                <li
                                    class="py-3 px-2 border-t hover:bg-teal-600 hover:text-white transition"
                                >
                                    {{ t("home.package.feature") }}
                                </li>
                            </ul>
                        </div>
                        <a
                            href="#_"
                            class="relative inline-flex items-center justify-start px-6 py-3 overflow-hidden font-medium transition-all bg-teal-600 rounded hover:bg-bg-teal-600 group border-white"
                        >
                            <span
                                class="w-48 h-48 rounded rotate-[-40deg] bg-[#f9be09] absolute bottom-0 left-0 -translate-x-full ease-out duration-500 transition-all translate-y-full mb-9 ml-9 group-hover:ml-0 group-hover:mb-32 group-hover:translate-x-0"
                            ></span>
                            <span
                                class="relative w-full text-left text-white transition-colors duration-300 ease-in-out group-hover:text-black"
                            >
                                {{ t("home.package.chooseBtn") }}</span
                            >
                        </a>
                        <div>
                            <span
                                :class="[
                                    'absolute top-7 z-[-1]',
                                    locale === 'ar'
                                        ? 'left-0 rotate-180'
                                        : 'right-0 img-rotate-180',
                                ]"
                            >
                                <img
                                    src="images/home/home/min_circle.svg"
                                    alt=""
                                />
                            </span>
                            <span
                                :class="[
                                    'absolute top-4 z-[-1]',
                                    locale === 'ar' ? 'left-4' : 'right-4',
                                ]"
                            >
                                <img
                                    src="images/home/home/min_pointes.svg"
                                    alt=""
                                />
                            </span>
                        </div>
                    </div>
                </div>
                <div
                    data-aos="zoom-out-up"
                    class="w-full overflow-hidden md:w-1/2 lg:w-1/3 px-4 relative"
                >
                    <div
                        class="bg-white rounded-xl relative z-10 overflow-hidden shadow-xl border-primary border-opacity-20 shadow-pricing py-10 px-8 sm:p-12 lg:py-10 lg:px-6 xl:p-12 mb-10"
                    >
                        <span
                            class="text-primary font-semibold text-lg block mb-4"
                        >
                            {{ t("home.package.basicPlan") }}
                        </span>
                        <h2 class="font-bold mb-5 text-[42px] text-teal-600">
                            $124
                            <span class="text-base text-body-color font-medium">
                                / {{ t("home.package.year") }}
                            </span>
                        </h2>
                        <p
                            class="text-base text-body-color pb-8 mb-8 border-[#F2F2F2]"
                        >
                            {{ t("home.package.description") }}
                        </p>
                        <div class="mb-7">
                            <ul>
                                <li
                                    class="py-3 px-2 border-t hover:bg-teal-600 hover:text-white transition"
                                >
                                    {{ t("home.package.feature") }}
                                </li>
                                <li
                                    class="py-3 px-2 border-t hover:bg-teal-600 hover:text-white transition"
                                >
                                    {{ t("home.package.feature") }}
                                </li>
                                <li
                                    class="py-3 px-2 border-t hover:bg-teal-600 hover:text-white transition"
                                >
                                    {{ t("home.package.feature") }}
                                </li>
                                <li
                                    class="py-3 px-2 border-t hover:bg-teal-600 hover:text-white transition"
                                >
                                    {{ t("home.package.feature") }}
                                </li>
                                <li
                                    class="py-3 px-2 border-t hover:bg-teal-600 hover:text-white transition"
                                >
                                    {{ t("home.package.feature") }}
                                </li>
                                <li
                                    class="py-3 px-2 border-t hover:bg-teal-600 hover:text-white transition"
                                >
                                    {{ t("home.package.feature") }}
                                </li>
                                <li
                                    class="py-3 px-2 border-t hover:bg-teal-600 hover:text-white transition"
                                >
                                    {{ t("home.package.feature") }}
                                </li>
                                <li
                                    class="py-3 px-2 border-t hover:bg-teal-600 hover:text-white transition"
                                >
                                    {{ t("home.package.feature") }}
                                </li>
                            </ul>
                        </div>
                        <a
                            href="#_"
                            class="relative inline-flex items-center justify-start px-6 py-3 overflow-hidden font-medium transition-all bg-[#f9be09] rounded hover:bg-bg-teal-600 group border-white"
                        >
                            <span
                                class="w-48 h-48 rounded rotate-[-40deg] bg-teal-600 absolute bottom-0 left-0 -translate-x-full ease-out duration-500 transition-all translate-y-full mb-9 ml-9 group-hover:ml-0 group-hover:mb-32 group-hover:translate-x-0"
                            ></span>
                            <span
                                class="relative w-full text-left text-black transition-colors duration-300 ease-in-out group-hover:text-white"
                            >
                                {{ t("home.package.chooseBtn") }}</span
                            >
                        </a>
                        <div>
                            <span
                                :class="[
                                    'absolute  top-7 z-[-1]',
                                    locale === 'ar'
                                        ? 'left-0 rotate-180'
                                        : 'right-0 img-rotate-180',
                                ]"
                            >
                                <img
                                    src="images/home/home/min_circle.svg"
                                    alt=""
                                />
                            </span>
                            <!-- <span class="absolute left-4 top-4 z-[-1]"> -->
                            <span
                                :class="[
                                    'absolute top-4 z-[-1]',
                                    locale === 'ar' ? 'left-4' : 'right-4',
                                ]"
                            >
                                <img
                                    src="images/home/home/min_pointes.svg"
                                    alt=""
                                />
                            </span>
                        </div>
                    </div>
                </div>
                <div
                    data-aos="zoom-out-down"
                    class="w-full overflow-hidden md:w-1/2 lg:w-1/3 px-4 relative"
                >
                    <div
                        class="bg-white rounded-xl relative z-10 overflow-hidden shadow-xl border-primary border-opacity-20 shadow-pricing py-10 px-8 sm:p-12 lg:py-10 lg:px-6 xl:p-12 mb-10"
                    >
                        <span
                            class="text-primary font-semibold text-lg block mb-4"
                        >
                            {{ t("home.package.basicPlan") }}
                        </span>
                        <h2 class="font-bold mb-5 text-[42px] text-teal-600">
                            $220
                            <span class="text-base text-body-color font-medium">
                                / {{ t("home.package.year") }}
                            </span>
                        </h2>
                        <p
                            class="text-base text-body-color pb-8 mb-8 border-[#F2F2F2]"
                        >
                            {{ t("home.package.description") }}
                        </p>
                        <div class="mb-7">
                            <ul>
                                <li
                                    class="py-3 px-2 border-t hover:bg-teal-600 hover:text-white transition"
                                >
                                    {{ t("home.package.feature") }}
                                </li>
                                <li
                                    class="py-3 px-2 border-t hover:bg-teal-600 hover:text-white transition"
                                >
                                    {{ t("home.package.feature") }}
                                </li>
                                <li
                                    class="py-3 px-2 border-t hover:bg-teal-600 hover:text-white transition"
                                >
                                    {{ t("home.package.feature") }}
                                </li>
                                <li
                                    class="py-3 px-2 border-t hover:bg-teal-600 hover:text-white transition"
                                >
                                    {{ t("home.package.feature") }}
                                </li>
                                <li
                                    class="py-3 px-2 border-t hover:bg-teal-600 hover:text-white transition"
                                >
                                    {{ t("home.package.feature") }}
                                </li>
                                <li
                                    class="py-3 px-2 border-t hover:bg-teal-600 hover:text-white transition"
                                >
                                    {{ t("home.package.feature") }}
                                </li>
                                <li
                                    class="py-3 px-2 border-t hover:bg-teal-600 hover:text-white transition"
                                >
                                    {{ t("home.package.feature") }}
                                </li>
                                <li
                                    class="py-3 px-2 border-t hover:bg-teal-600 hover:text-white transition"
                                >
                                    {{ t("home.package.feature") }}
                                </li>
                            </ul>
                        </div>
                        <a
                            href="#_"
                            class="relative inline-flex items-center justify-start px-6 py-3 overflow-hidden font-medium transition-all bg-teal-600 rounded hover:bg-bg-teal-600 group border-white"
                        >
                            <span
                                class="w-48 h-48 rounded rotate-[-40deg] bg-[#f9be09] absolute bottom-0 left-0 -translate-x-full ease-out duration-500 transition-all translate-y-full mb-9 ml-9 group-hover:ml-0 group-hover:mb-32 group-hover:translate-x-0"
                            ></span>
                            <span
                                class="relative w-full text-left text-white transition-colors duration-300 ease-in-out group-hover:text-black"
                            >
                                {{ t("home.package.chooseBtn") }}</span
                            >
                        </a>
                        <div>
                            <span
                                :class="[
                                    'absolute top-7 z-[-1]',
                                    locale === 'ar'
                                        ? 'left-0 rotate-180'
                                        : 'right-0 img-rotate-180',
                                ]"
                            >
                                <img
                                    src="images/home/home/min_circle.svg"
                                    alt=""
                                />
                            </span>
                            <span
                                :class="[
                                    'absolute top-4 z-[-1]',
                                    locale === 'ar' ? 'left-4' : 'right-4',
                                ]"
                            >
                                <img
                                    src="images/home/home/min_pointes.svg"
                                    alt=""
                                />
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<style>
.img-rotate-180 {
    transform: rotateX(3.142rad);
}
</style>
