<script setup>
import { ref, watch, watchEffect } from "vue";
import { useI18n } from "vue-i18n";
import { storeToRefs } from "pinia";
import { useStatisticStore } from "../../../store/pinia/statistic";
import { getDataLabelByQuery } from "../../../utils";
const {
    mostServices,
    bestServices,
    mostServicedItems,
    loading,
    currentTabIndex,
} = storeToRefs(useStatisticStore());

const { t } = useI18n();

const seriesRef = ref([]);
const optionsRef = ref({
    // colors: ["#4BC49A", "#3699FF", "#F53488", "#A53488","#753488"],
    colors: ["#4BC49A", "#3699FF", "#F53488", "#1d5f28", "#a9c951"],
    chart: {
        type: "line",
        height: "auto",
        width: "100%",
        zoom: {
            enabled: false,
        },
        toolbar: {
            show: false,
        },
        foreColor: "#373d3f",
    },
    dataLabels: {
        enabled: false,
    },
    stroke: {
        curve: "straight",
    },
    // fill: {
    //   colors: ['#4BC49A'],
    //   type: "gradient",
    //   gradient: {
    //     shadeIntensity: 1,
    //     opacityFrom: 0.7,
    //     opacityTo: 0.9,
    //     stops: [0, 90, 100]
    //   }
    // },

    title: {
        text: t("analytics.chart.bestSellingServices"), //'الخدمات الأكثر مبيعاً',
        align: "right",
        style: {
            fontSize: "16px",
            fontWeight: "normal",
            fontFamily: "Readex Pro",
            color: "#0D0D0D",
        },
    },
    subtitle: {
        text: "",
        align: "right",
        style: {
            fontSize: "36px",
            fontWeight: "normal",
            fontFamily: "Readex Pro",
            color: "#484860",
        },
    },
    xaxis: {
        // categories: getDataLabelByQuery(currentTabIndex.value),
        categories: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
    },
    yaxis: {
        opposite: true,
    },
    legend: {
        horizontalAlign: "left",
    },
});

watchEffect(() => {
    optionsRef.value = {
        ...optionsRef.value,
        title: {
            ...optionsRef.value.title,
            text: t("analytics.chart.bestSellingServices"),
        },
    };
});

// const computedOptions = computed(() => ({
//     ...optionsRef.value,
//     title: {
//         ...optionsRef.value.title,
//         text: t("analytics.chart.bestSellingServices"),
//     },
// }));

// watch(currentTabIndex, async (newIndex, oldIndex) => {
//     optionsRef.value = {
//         ...optionsRef.value,
//         ...{
//             xaxis: {
//                 categories: getDataLabelByQuery(newIndex),
//             },
//         },
//     };
// });

watch(bestServices, async (newServices, oldServices) => {
    // let dataArr = newServices.map((s, index) => ({
    //     name: `${s["service"]}`,
    //     data: s["total"],
    // }));
    // seriesRef.value = [...dataArr];
    if (newServices) {
        // Check if newServices is defined
        let dataArr = newServices.map((s, index) => ({
            name: `${s["service"]}`,
            data: s["total_arr"],
        }));
        seriesRef.value = [...dataArr];
    } else {
        // Handle the case where newServices is undefined,
        // maybe set seriesRef.value to an empty array or keep the old data
        seriesRef.value = [];
    }
});
</script>

<template>
    <div>
        <!-- <apexchart type="line" :options="optionsRef" :series="seriesRef" class="mx-auto"></apexchart> -->
        <apexchart
            type="bar"
            :options="optionsRef"
            :series="seriesRef"
            class="mx-auto"
        ></apexchart>
    </div>
</template>

<style lang="scss" scoped></style>
