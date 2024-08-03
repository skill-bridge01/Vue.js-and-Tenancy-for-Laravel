<script setup>
import { ref, watch, computed } from "vue";
import { useI18n } from "vue-i18n";
import { storeToRefs } from "pinia";
import { useStatisticStore } from "../../../store/pinia/statistic";

const { t } = useI18n();
const viewAllStatus = ref(false);
const emits = defineEmits(["viewAllActive"]);
const active = computed({
    get() {
        return props.modalActive;
    },
    set(val) {
        emits("viewAllActive", val);
    },
});

const handleClickViewAll = () => {
    viewAllStatus.value = !viewAllStatus.value;
    emits("viewAllActive", false);
};

const props = defineProps({
    title: String,
    options: {
        type: Object,
        default: {
            headerLabels: [],
            data: [],
        },
    },
    dataType: {
        type: String,
        default: "invoices",
    },
});
</script>
<template>
    <div class="p-4 rounded-md shadow-md bg-white">
        <div
            class="flex items-center mb-4"
            :class="dataType === 'invoices' ? 'justify-between' : 'justify-end'"
        >
            <button
                @click="handleClickViewAll"
                v-if="dataType === 'invoices'"
                type="button"
                class="underline text-main"
            >
            <p v-if="viewAllStatus">{{ t("analytics.chart.viewLess") }}</p>
            <p v-else>{{ t("analytics.chart.viewAll") }}</p>
                
            </button>
            <p class="font-readex text-base text-[#0D0D0D]">
                {{ title }}
            </p>
        </div>
        <div class="rounded-2xl border border-solid border-[#E2E8F0]">
            <table>
                <thead>
                    <tr>
                        <th
                            v-for="label in props.options.headerLabels"
                            :key="label"
                        >
                            {{ label }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- {{ props.options.value.data }} -->
                    <tr v-for="row in props.options.data" :key="row">
                        <td>
                            {{ row.amount }}
                        </td>
                        <td>
                            {{ row.service_name }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<style lang="scss" scoped></style>
