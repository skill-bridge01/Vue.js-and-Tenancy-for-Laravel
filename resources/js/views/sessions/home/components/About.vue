<script setup>
import { useI18n } from "vue-i18n";

const { t, locale } = useI18n();

</script>
<template>
    <section class="py-16" id="about">
        <div class="container mx-auto">
            <div class="flex flex-wrap -mx-4">
                <div :class="['w-full px-4',locale=='ar'?'py-12':'pt-24 pb-12']">
                    <div
                        data-aos="zoom-out"
                        class="text-center mx-auto mb-[60px] lg:mb-20 max-w-[510px]"
                    >
                        <span class="font-semibold text-lg text-primary block">
                            {{ t("home.about.aboutSystem") }}
                        </span>
                        <h2
                            :class="[' leading-7 md:text-[40px] text-dark mb-4  mt-4 max-w-sm  from-gray-300 via-teal-600 to-gray-300 bg-clip-text text-center text-4xl font-extrabold text-transparent sm:max-w-4xl sm:text-6xl flex items-center justify-center', locale==='ar'?'bg-gradient-to-br h-14':'bg-gradient-to-bl']"
                        >
                            {{ t("home.about.title") }}
                        </h2>
                    </div>
                </div>
            </div>
            <div
                class="grid lg:grid-cols-3 2xl:grid-cols-4 grid-cols-1 gap-10 antialiased"
            >
                <article
                    class="flex flex-col shadow-xl hover:shadow-2xl mx-auto max-w-sm bg-white py-16 px-12 border-neutral-900 transform duration-500 hover:-translate-y-2 cursor-pointer max-h-190 rounded-md"
                >
                    <h2 class="font-bold mb-5 text-teal-600">
                        {{ t("home.about.card-one.title") }}
                    </h2>
                    <p class="text-sm leading-relaxed text-neutral-800">
                        {{ t("home.about.card-one.content") }}
                    </p>
                </article>

                <article
                    class="flex flex-col shadow-xl hover:shadow-2xl mx-auto max-w-sm bg-white py-16 px-12 border-neutral-900 transform duration-500 hover:-translate-y-2 cursor-pointer mt-0 max-h-190 rounded-md"
                >
                    <h2 class="font-bold mb-5 text-teal-600">
                        {{ t("home.about.card-two.title") }}
                    </h2>
                    <p class="text-sm leading-relaxed text-neutral-800">
                        {{ t("home.about.card-two.content") }}
                    </p>
                </article>

                <article
                    class="flex flex-col shadow-xl hover:shadow-2xl mx-auto max-w-sm bg-white py-16 px-12 border-neutral-900 transform duration-500 hover:-translate-y-2 cursor-pointer max-h-190 rounded-md"
                >
                    <h2 class="font-bold mb-5 text-teal-600">
                        {{ t("home.about.card-three.title") }}
                    </h2>
                    <p class="text-sm leading-relaxed text-neutral-800">
                        {{ t("home.about.card-three.content") }}
                    </p>
                </article>
                <article
                    class="flex flex-col shadow-xl hover:shadow-2xl mx-auto max-w-sm bg-white py-16 px-12 border-neutral-900 transform duration-500 hover:-translate-y-2 cursor-pointer mt-0 max-h-190 rounded-md"
                >
                    <h2 class="font-bold mb-5 text-teal-600">
                        {{ t("home.about.card-four.title") }}
                    </h2>
                    <p class="text-sm leading-relaxed text-neutral-800">
                        {{ t("home.about.card-four.content") }}
                    </p>
                </article>
                <article
                    class="flex flex-col shadow-xl hover:shadow-2xl mx-auto max-w-sm bg-white py-16 px-12 border-neutral-900 transform duration-500 hover:-translate-y-2 cursor-pointer max-h-190 rounded-md"
                >
                    <h2 class="font-bold mb-5 text-teal-600">
                        {{ t("home.about.card-five.title") }}
                    </h2>
                    <p class="text-sm leading-relaxed text-neutral-800">
                        {{ t("home.about.card-five.content") }}
                    </p>
                </article>
                <article
                    class="flex flex-col shadow-xl hover:shadow-2xl mx-auto max-w-sm bg-white py-16 px-12 border-neutral-900 transform duration-500 hover:-translate-y-2 cursor-pointer mt-0 max-h-190 rounded-md"
                >
                    <h2 class="font-bold mb-5 text-teal-600">
                        {{ t("home.about.card-six.title") }}
                    </h2>
                    <p class="text-sm leading-relaxed text-neutral-800">
                        {{ t("home.about.card-six.content") }}
                    </p>
                </article>
            </div>
        </div>
    </section>
</template>
