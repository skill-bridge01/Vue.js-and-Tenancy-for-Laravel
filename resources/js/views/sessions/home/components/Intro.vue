<script setup>
import { useI18n } from "vue-i18n";

const { t, locale } = useI18n();
</script>
<template>
    <header class="relative" id="home">
        <div class="container mx-auto lg:flex items-center">
            <div
                class="flex flex-col items-start justify-center py-16 ps-4 lg:ps-16"
                :data-aos="[locale=='ar'?'fade-left':'fade-right']"
            >
                <h1 class="text-4xl py-2 text-black lg:text-white">
                    {{ t("home.intro.title_easy") }}
                    <span class="text-[#f9be09]">{{
                        t("home.intro.title_fast")
                    }}</span>
                </h1>
                <p
                    class="lg:w-8/12 leading-8 pb-6 text-black lg:text-white py-6"
                >
                    {{ t("home.intro.content") }}
                </p>
                <a
                    href="#_"
                    class="relative inline-flex items-center justify-start px-6 py-3 overflow-hidden font-medium transition-all bg-white rounded hover:bg-white group"
                >
                    <span
                        class="w-48 h-48 rounded rotate-[-40deg] bg-[#f9be09] absolute bottom-0 left-0 -translate-x-full ease-out duration-500 transition-all translate-y-full mb-9 ml-9 group-hover:ml-0 group-hover:mb-32 group-hover:translate-x-0"
                    ></span>
                    <span
                        class="relative w-full text-left text-black transition-colors duration-300 ease-in-out group-hover:text-teal-50"
                        >{{ t("home.intro.tryBtn") }}
                    </span>
                </a>
            </div>
            <div
                class="flex items-center justify-center h-full lg:py-20 lg:pe-20 px-4 relative animate-upDown"
                :data-aos="[locale=='ar'?'fade-right':'fade-left']"
            >
                <div>
                    <img
                        class="absolute w-32 top-12 left-16 opacity-10"
                        src="images/home/home/shapes.svg"
                        width="64"
                        alt=""
                    />
                    <img
                        class="w-full relative"
                        src="images/home/home/mockup.png"
                        width="300"
                        alt=""
                    />
                </div>
            </div>
        </div>
    </header>
</template>
