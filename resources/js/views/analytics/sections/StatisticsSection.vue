<script setup>
import { ref, watch, watchEffect } from "vue";
import { useI18n } from "vue-i18n";
import { storeToRefs } from "pinia";
import { useStatisticStore } from "../../../store/pinia/statistic";
import DemandService from "../components/DemandService.vue";
import StatisticTable from "../components/StatisticTable.vue";
import ServiceStatisticTable from "../components/ServiceStatisticTable.vue";
import {
    getTableOptionsForCustomers,
    getTableOptionsForServices,
} from "../../../utils";

const { t } = useI18n();

const viewAllRequestedServices = ref(false);
const viewAllActive = () => {
    console.log("efefef");
    viewAllRequestedServices.value = !viewAllRequestedServices.value;
};
const {
    mostPurchasedCustomerArr,
    bestServices,
    totalServices,
    loading,
    currentTabIndex,
} = storeToRefs(useStatisticStore());

const customerTableOptions = ref({
    headerLabels: [
        t("analytics.chart.totalBills"),
        t("analytics.chart.customer"),
    ],
    data: getTableOptionsForCustomers(mostPurchasedCustomerArr.value),
});
const invoiceTableOptions = ref({
    headerLabels: [t("analytics.chart.value"), t("analytics.chart.service")],
    data: getTableOptionsForServices(bestServices.value),
});

watchEffect(() => {
    invoiceTableOptions.value.headerLabels = [
        t("analytics.chart.value"),
        t("analytics.chart.service"),
    ];
    customerTableOptions.value.headerLabels = [
        t("analytics.chart.totalBills"),
        t("analytics.chart.customer"),
    ];
});

watch(mostPurchasedCustomerArr, async (newCustomerArr, oldCustomerArr) => {
    console.log("newCustomerArr", newCustomerArr);
    customerTableOptions.value = {
        ...customerTableOptions.value,
        data: getTableOptionsForCustomers(newCustomerArr),
    };
});

watchEffect(() => {
    // This effect will re-run whenever `viewAllRequestedServices` changes
    if (viewAllRequestedServices.value) {
        // Use a standalone watch within watchEffect, and stop it when condition changes
        const stop = watch(
            totalServices,
            async (requestedServicesArr, oldCustomerArr) => {
                // handle total services
                console.log("requestedAllServicesArr", requestedServicesArr);
                invoiceTableOptions.value = {
                    ...invoiceTableOptions.value,
                    data: getTableOptionsForServices(requestedServicesArr),
                };
            },
            { immediate: true }
        );

        // Return a cleanup function that stops the watch when the effect re-runs or the component unmounts
        return stop;
    } else {
        // Similar setup for bestServices
        const stop = watch(
            bestServices,
            async (requestedServicesArr, oldCustomerArr) => {
                // handle best services
                console.log("requestedServicesArr", requestedServicesArr);
                invoiceTableOptions.value = {
                    ...invoiceTableOptions.value,
                    data: getTableOptionsForServices(requestedServicesArr),
                };
            },
            { immediate: true }
        );

        return stop;
    }
});

if (viewAllRequestedServices) {
    watch(totalServices, async (requestedServicesArr, oldCustomerArr) => {
        console.log("requestedServicesArr", requestedServicesArr);
        invoiceTableOptions.value = {
            ...invoiceTableOptions.value,
            data: getTableOptionsForServices(requestedServicesArr),
        };
    });
} else {
    watch(bestServices, async (requestedServicesArr, oldCustomerArr) => {
        console.log("requestedServicesArr", requestedServicesArr);
        invoiceTableOptions.value = {
            ...invoiceTableOptions.value,
            data: getTableOptionsForServices(requestedServicesArr),
        };
    });
}
</script>
<template>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <StatisticTable
            data-type="customers"
            :title="t('analytics.chart.mostPurchasedCustomers')"
            :table-options="customerTableOptions"
        />
        <!-- <DemandService class="p-4 rounded-md shadow-md bg-white" /> -->

        <ServiceStatisticTable
            data-type="invoices"
            :title="t('analytics.chart.mostRequestedServices')"
            @view-all-active="viewAllActive"
            :options="invoiceTableOptions"
        />
    </div>
</template>
