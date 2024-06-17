<script setup>
import { onMounted, ref, watch, watchEffect, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { Switch } from '@headlessui/vue'
import HeaderSearch from '../components/HeaderSearch.vue'
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import { useStore } from 'vuex'
import { useFullscreen } from '@vueuse/core'
import { useRoute } from 'vue-router'

const LOCALES = [
    {
        label: 'EN',
        value: 'en'
    },
    {
        label: 'AR',
        value: 'ar'
    }
]

const { t, locale } = useI18n();
const selectedLocale = ref(locale.value)
const { isFullscreen, toggle: toggleFullScreen } = useFullscreen()

let store = useStore()
const route = useRoute()

onMounted(() => {
    console.log(route.meta.title)
})

watchEffect(() => {
    locale.value = LOCALES.find((l) => l.value === selectedLocale.value).value
})

const sideNavOpen = computed(() => {
    console.log('open')
    return store.state.largeSidebar.sidebarToggleProperties.isSideNavOpen
});


const sideBarToggle = () => {
    let sidenav = store.state.largeSidebar.sidebarToggleProperties.isSideNavOpen

    if (sidenav == false) {
        store.commit('largeSidebar/toggleSidebarProperties')
    } else {
        store.commit('largeSidebar/toggleSidebarProperties')
    }
}
</script>

<template>
    <div class="header-wrapper sidebar-open flex bg-white justify-between px-4"
        :class="sideNavOpen ? 'sidebar-open' : 'sidebar-close'">
        <div class="flex items-center">
            <div class="mx-0 sm:mx-3">
                <button @click="toggleFullScreen" class="
                        menu-toggle
                        cursor-pointer
                        text-gray-500
                        align-middle
                        focus:outline-none
                    " type="button">
                    <img :src="isFullscreen ? '/images/header/full-screen-cancel.svg' : '/images/header/full-screen.svg'"
                        alt="fullscreen" class="flex-shrink-0 w-6 h-6 object-cover">
                </button>
            </div>
        </div>
        <div class="flex items-center">
            <HeaderSearch v-if="route.name.toLocaleLowerCase() === 'dashboards'" />
            <div class="px-4">
                <select v-model="selectedLocale" class="border-none outline-none focus:outline-none">
                    <option v-for="l in LOCALES" :value="l.value">{{ l.label }}</option>
                </select>
            </div>
            <div class="flex items-center">
                <div>{{ t('dashboard.companyName') }}</div>
                <div class="mx-0 sm:mx-3">
                    <button @click="sideBarToggle" class="
                            menu-toggle
                            cursor-pointer
                            text-gray-500
                            align-middle
                            focus:outline-none
                        " type="button">
                        <img :src="sideNavOpen ? '/images/header/IconHolder_open.svg' : '/images/header/IconHolder_close.svg'"
                            alt="Menu" class="flex-shrink-0 w-full h-full object-cover">
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.header-wrapper {
    position: fixed;
    top: 0;
    width: calc(100% - 60px);
    height: 80px;
    z-index: 100;
    box-shadow: 0 1px 15px rgb(0 0 0 / 4%), 0 1px 6px rgb(0 0 0 / 4%);
    transition: all 0.4s ease-in-out;
}

.sidebar-open {
    width: calc(100% - 240px);
    transition: all 0.4s ease-in-out;
}

.sidebar-close {
    width: calc(100% - 60px);
    transition: all 0.4s ease-in-out;

    @media screen and (max-width: 991px) {
        width: 100%;
    }
}

.mega-menu {
    width: 1200px;
}

ul.links {
    column-count: 2;

    li {
        margin-bottom: 8px;
    }
}
</style>
