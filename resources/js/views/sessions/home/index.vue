<script setup>
import "./style.css";
import Intro from "./components/Intro.vue";
import About from "./components/About.vue";
import Package from "./components/Package.vue";
import Contact from "./components/Contact.vue";
import { useI18n } from "vue-i18n";
import { ref, watchEffect, onMounted, onUpdated } from "vue";
import AOS from "aos";

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

onMounted(() => {
    // AOS.init();
    AOS.init({
        offset: 80, // offset (in px) from the original trigger point
        duration: 1000, // values from 0 to 3000, with step 50ms
        once: false, // whether animation should happen only once - while scrolling down
    });
});

const isNavOpen = ref(false);

// onUpdated(() => {
//     AOS.refresh();
// });

const toggleNav = () => {
    isNavOpen.value = !isNavOpen.value;
};

const { t, locale } = useI18n();

const selectedLocale = ref(locale.value);

watchEffect(() => {
    locale.value = LOCALES.find((l) => l.value === selectedLocale.value).value;
});
</script>
<template>
    <div class="font" :dir="[locale === 'en' ? 'ltr' : 'rtl']">
        <div class="relative">
            <div
                :class="[
                    'w-10/12 h-[40rem] absolute  top-0',
                    locale == 'ar'
                        ? 'right-0 bg-gradient-to-l from-teal-600 to-white'
                        : 'left-0 bg-gradient-to-r from-teal-600 to-white',
                ]"
            ></div>

            <nav class="px-6 py-4 shadow-md relative">
                <div
                    class="container mx-auto flex items-center justify-between flex-wrap"
                >
                    <div class="flex items-center flex-shrink-0 w-[18%] mr-6">
                        <!-- <span
                            class="font-semibold text-xl tracking-tight text-neutral-50 lg:ps-10"
                            >{{ t("home.nav.logo") }}
                        </span> -->
                        <img src="/images/laundrylogo/32.png" class="scale-80" />
                    </div>
                    <div class="block lg:hidden">
                        <button
                            @click="toggleNav"
                            class="flex items-center px-3 py-2 border rounded text-teal-200 border-teal-700 hover:border-white rounded-e-xl"
                            id="btn-nav"
                        >
                            <svg
                                class="fill-current h-3 w-3"
                                viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <title>Menu</title>
                                <path
                                    d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"
                                />
                            </svg>
                        </button>
                    </div>
                    <div
                        :class="{ 'h-fit': isNavOpen }"
                        class="w-full block flex-grow lg:flex lg:items-center lg:w-auto overflow-hidden lg:h-full h-0 transition"
                        id="nav-section"
                    >
                        <div class="text-sm lg:flex-grow">
                            <a
                                href="#home"
                                class="relative mt-4 inline-block lg:mt-0 text-neutral-800 text-lg hover:text-neutral-800 lg:mr-8 mr-6 after:block after:content-[''] after:absolute after:h-[3px] after:bg-white after:w-full after:scale-x-0 after:hover:scale-x-100 after:transition after:duration-300 after:origin-right"
                            >
                                {{ t("home.nav.main") }}
                            </a>
                            <a
                                href="#about"
                                class="relative block mt-4 lg:inline-block lg:mt-0 text-neutral-800 hover:text-neutral-800 text-lg lg:mr-12 mr-4 after:block after:content-[''] after:absolute after:h-[3px] after:bg-white after:w-full after:scale-x-0 after:hover:scale-x-100 after:transition after:duration-300 after:origin-right"
                            >
                                {{ t("home.nav.about") }}
                            </a>
                            <a
                                href="#price"
                                class="relative w-fit mt-4 inline-block lg:mt-0 text-neutral-800 hover:text-neutral-800 text-lg lg:mr-12 mr-4 after:block after:content-[''] after:absolute after:h-[3px] after:bg-white after:w-full after:scale-x-0 after:hover:scale-x-100 after:transition after:duration-300 after:origin-right pb-8 lg:pb-0"
                            >
                                {{ t("home.nav.priceAndPackage") }}
                            </a>
                        </div>
                        <div class="flex flex-col lg:flex-row gap-3">
                            <!-- <div
                                :class="[
                                    '',
                                    locale === 'ar' ? 'pl-10' : 'pr-10',
                                ]"
                            > -->
                            <div>
                                <select
                                    data-type="home"
                                    v-model="selectedLocale"
                                    class="rounded-md border-2 h-12 border-teal-700 outline-none focus:outline-none"
                                >
                                    <option
                                        v-for="l in LOCALES"
                                        :value="l.value"
                                    >
                                        {{ l.label }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <router-link to="/register">
                                    <a
                                        class="relative inline-flex items-center justify-start px-6 py-3 overflow-hidden font-medium transition-all bg-teal-600 rounded hover:bg-teal-600 group border-white"
                                    >
                                        <span
                                            class="w-48 h-48 rounded rotate-[-40deg] bg-[#f9be09] absolute bottom-0 left-0 -translate-x-full ease-out duration-500 transition-all translate-y-full mb-9 ml-9 group-hover:ml-0 group-hover:mb-32 group-hover:translate-x-0"
                                        ></span>
                                        <span
                                            class="relative w-full text-left text-white transition-colors duration-300 ease-in-out group-hover:text-black"
                                        >
                                            {{ t("sidebar.menu.register") }}
                                        </span>
                                    </a>
                                </router-link>
                            </div>
                            <div>
                                <a
                                    href="#contact"
                                    class="relative inline-flex items-center justify-start px-6 py-3 overflow-hidden font-medium transition-all bg-teal-600 rounded hover:bg-teal-600 group border-white"
                                >
                                    <span
                                        class="w-48 h-48 rounded rotate-[-40deg] bg-[#f9be09] absolute bottom-0 left-0 -translate-x-full ease-out duration-500 transition-all translate-y-full mb-9 ml-9 group-hover:ml-0 group-hover:mb-32 group-hover:translate-x-0"
                                    ></span>
                                    <span
                                        class="relative w-full text-left text-white transition-colors duration-300 ease-in-out group-hover:text-black"
                                    >
                                        {{ t("home.nav.contact") }}
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            <Intro />
        </div>
        <main class="overflow-hidden">
            <About />
            <Package />
            <Contact />
        </main>
    </div>
</template>
