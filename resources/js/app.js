import { createApp } from 'vue'
import { createI18n } from 'vue-i18n'
import App from './App.vue'
import router from './router'
import store from './store'
import en from './locales/en.json'
import ar from './locales/ar.json'

import { createPinia } from "pinia";
import { useMainStore } from "@/store/pinia/main.js";

import VueTelInput from 'vue-tel-input';
import 'vue-tel-input/vue-tel-input.css';

import './assets/scss/global.scss'
import './styles/index.css'
import BaseCard from './components/Base/BaseCard.vue'
import BaseBtn from './components/Base/BaseBtn.vue'
import FullHeightCard from './components/Base/FullHeightCard.vue'
import WidgetButton from './components/Base/WidgetButton.vue'
import ServiceButton from './components/Base/ServiceButton.vue'

// perfectscrollbar plugins 
import PerfectScrollbar from 'vue3-perfect-scrollbar'
import 'vue3-perfect-scrollbar/dist/vue3-perfect-scrollbar.css'
import VueApexCharts from "vue3-apexcharts";

import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import  VueSelect from "vue-select";
import Page from 'v-page'
import PrimeVue from 'primevue/config';
import AutoComplete from "primevue/autocomplete"
import "primevue/resources/themes/lara-light-indigo/theme.css";
import { create } from 'lodash'
import VOtpInput from "vue3-otp-input";

/* Init Pinia */
const pinia = createPinia();

const globalOptions = {
    mode: 'national',
    // autoFormat: true,
    preferredCountries: ["SA"],
    styleClasses: [
        'w-full',
        'border-0',
        'focus-shadow-none'
    ],
    disabledFormatting: true,
    dropdownOptions: {
        showSearchBox: true,
    },
    inputOptions: {
        // numericOnly: true,
        // showDialCode: true,
        placeholder: 'رقم الهاتف',
        styleClasses: [
            'focus:outline-none',
            'text-right'
        ]
    }
  };

// i18n configuration
const i18n = createI18n({
    locale: 'en',
    fallbackLocale: 'en',
    messages: { en, ar },
    legacy: false
})

createApp(App)
    .component('BaseCard', BaseCard)
    .component('BaseBtn', BaseBtn)
    .component('FullHeightCard', FullHeightCard)
    .component('WidgetButton', WidgetButton)
    .component('ServiceButton', ServiceButton)
    .component('VueDatePicker', VueDatePicker)
    .component("v-select", VueSelect)
    .component('AutoComplete', AutoComplete)
    .use(i18n)
    .use(VueTelInput, globalOptions)
    .use(PerfectScrollbar)
    .use(VueApexCharts)
    .use(Page, {
        // globally config options
      })
    .use(store)
    .use(router)
    .use(pinia)
    .use(PrimeVue, { unstyled: true})
    .mount('#app')

    /* Init Pinia stores */
const mainStore = useMainStore(pinia);
/* Fetch sample data */
mainStore.fetch("clients");
mainStore.fetch("history");
