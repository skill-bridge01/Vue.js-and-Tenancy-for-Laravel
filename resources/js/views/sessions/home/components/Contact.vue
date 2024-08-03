<script setup>
import { useI18n } from "vue-i18n";

const { t, locale } = useI18n();
const date = new Date();
const year = date.getFullYear();
</script>
<template>
    <div
        class="mb-12 flex w-full flex-col text-center"
        id="contact"
        data-aos="zoom-out"
    >
        <div class="flex flex-wrap -mx-4">
            <div class="w-full px-4">
                <div
                    class="text-center mx-auto mb-[60px] lg:mb-20 max-w-[510px]"
                >
                    <span class="text-base text-body-color">
                        {{ t("home.contact.contact") }}
                    </span>
                    <h2
                        :class="['md:text-[40px] text-dark mb-4 mt-4max-w-sm  from-gray-500 via-teal-500 to-gray-500 bg-clip-text text-center text-4xl font-extrabold text-transparent sm:max-w-4xl sm:text-6xl flex items-center justify-center',locale==='ar'?'h-14 bg-gradient-to-br':'bg-gradient-to-bl']"
                    >
                        {{ t("home.contact.title") }}
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <section
        class="body-font relative right-0 top-0 bg-gradient-to-t from-teal-600 to-white"
    >
        <div class="container mx-auto px-5 pt-24 pb-10">
            <div class="">
                <div class="mx-auto md:w-2/3 lg:w-1/2">
                    <!-- form -->
                    <form class="-m-2 flex flex-wrap" action="">
                        <div class="w-1/2 p-2">
                            <div class="relative">
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    class="peer w-full rounded border bg-opacity-40 py-1 px-3 text-base leading-8 text-gray-100 placeholder-transparent outline-none transition-colors duration-200 ease-in-out focus:border-teal-600 focus:ring-2"
                                    :placeholder="t('home.contact.name')"
                                />
                                <label
                                    for="name"
                                    :class="[
                                        'absolute right-3 -top-6 bg-transparent text-sm leading-7 text-tborder-teal-600 transition-all peer-placeholder-shown:top-2 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-focus:right-3 peer-focus:-top-6 peer-focus:text-sm peer-focus:text-indigo-500',
                                        locale === 'ar'
                                            ? 'peer-placeholder-shown:right-3'
                                            : 'peer-placeholder-shown:left-3',
                                    ]"
                                    >{{ t("home.contact.name") }}</label
                                >
                            </div>
                        </div>
                        <div class="w-1/2 p-2">
                            <div class="relative">
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="peer w-full rounded border bg-opacity-40 py-1 px-3 text-base leading-8 text-gray-100 placeholder-transparent outline-none transition-colors duration-200 ease-in-out focus:border-teal-600 focus:ring-2"
                                    :placeholder="t('home.contact.email')"
                                />
                                <label
                                    for="email"
                                    :class="[
                                        'absolute right-3 -top-6 bg-transparent text-sm leading-7 text-tborder-teal-600 transition-all  peer-placeholder-shown:top-2 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-focus:right-3 peer-focus:-top-6 peer-focus:text-sm peer-focus:text-indigo-500',
                                        locale === 'ar'
                                            ? 'peer-placeholder-shown:right-3'
                                            : 'peer-placeholder-shown:left-3',
                                    ]"
                                    >{{ t("home.contact.email") }}</label
                                >
                            </div>
                        </div>
                        <div class="mt-4 w-full p-2">
                            <div class="relative">
                                <textarea
                                    id="message"
                                    name="message"
                                    class="peer h-32 w-full resize-none rounded border bg-opacity-40 py-1 px-3 text-base leading-6 text-gray-100 placeholder-transparent outline-none transition-colors duration-200 ease-in-out focus:border-teal-600 focus:ring-2"
                                    placeholder="Message"
                                ></textarea>
                                <label
                                    for="message"
                                    :class="[
                                        'absolute right-3 -top-6 bg-transparent text-sm leading-7 text-tborder-teal-600 transition-all  peer-placeholder-shown:top-2 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 peer-focus:left-3 peer-focus:-top-6 peer-focus:text-sm peer-focus:text-indigo-500',
                                        locale === 'ar'
                                            ? 'peer-placeholder-shown:right-3'
                                            : 'peer-placeholder-shown:left-3',
                                    ]"
                                    >{{ t("home.contact.message") }}</label
                                >
                            </div>
                        </div>
                        <div class="w-full p-2">
                            <a
                                href="#_"
                                class="px-5 py-2.5 relative rounded group overflow-hidden font-medium bg-teal-50 text-teal-600 inline-block"
                            >
                                <span
                                    class="absolute top-0 left-0 flex w-full h-0 mb-0 transition-all duration-200 ease-out transform translate-y-0 bg-teal-600 group-hover:h-full opacity-90"
                                ></span>
                                <span class="relative group-hover:text-white">
                                    {{ t("home.contact.btn") }}
                                </span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <footer class="">
            <div
                class="mt-8 pb-8 w-full bg border-t border-neutral-300 p-2 pt-8 text-center text-white"
            >
                {{ t("home.footer") }}
                <span class=""> {{ year }} </span>
            </div>
        </footer>
    </section>
</template>
