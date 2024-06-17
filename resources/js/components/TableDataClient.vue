<script setup>
import {
    computed,
    ref,
    onBeforeMount,
    onUnmounted,
    watchEffect,
    watch,
} from "vue";
import { useI18n } from "vue-i18n";
import QrcodeVue from "qrcode.vue";
import { useMainStore } from "@/store/pinia/main";
import {
    mdiEye,
    mdiTrashCan,
    mdiTrayArrowDown,
    mdiPrinterOutline,
    mdiPencil,
    mdiFileEyeOutline,
    mdiQrcode,
} from "@mdi/js";
import CardBoxModal from "@/components/CardBoxModal.vue";
import TableCheckboxCell from "@/components/TableCheckboxCell.vue";
import BaseLevel from "@/components/BaseLevel.vue";
import BaseIcon from "@/components/BaseIcon.vue";
import BaseButtons from "@/components/BaseButtons.vue";
import BaseButton from "@/components/BaseButton.vue";
import UserAvatar from "@/components/UserAvatar.vue";
import StatusLabel from "./StatusLabel.vue";
import PaginationBar from "./PaginationBar.vue";
import { format } from "date-fns";
import { Page } from "v-page";
// Importing the lodash library
import { sortBy } from "lodash";
import { mdiSortAscending, mdiSortDescending } from "@mdi/js";

import { useInvoicesStore } from "@/store/pinia/invoices";
import { storeToRefs } from "pinia";
import NewPaginationBar from "./NewPaginationBar.vue";
import { AtomSpinner } from "epic-spinners";
import TableInvoiceModal from "./TableInvoiceModal.vue";
import Vue3Html2pdf from "./vue3-html2pdf.vue";
import UpdateInvoiceStatusModal from "./UpdateInvoiceStatusModal.vue";
import QrcodeModal from "./QrcodeModal.vue";

defineProps({
    checkable: Boolean,
});

const { t } = useI18n();

const editModalActive = ref(false);
const qrcodeModalActive = ref(false);
const isQrModalActive = ref(false);
const qrValue = ref("laundry.sass.test:8000");
const qrSize = ref(300);
const html2Pdf = ref(null);

const invoicesStore = useInvoicesStore();
const { invoices, filteredInvoices, selectedInvoice, loading } = storeToRefs(
    useInvoicesStore()
);

const isModalActive = ref(false);

const isModalDangerActive = ref(false);

const perPage = ref(5);

const currentPage = ref(1);

const checkedRows = ref([]);

const invoiceModalActive = ref(false);

const activeInvoice = ref(null);

// a value to check for sort
const sort = ref(false);
const ascending = ref(false);
const sortColumn = ref("");
const updatedList = ref([]);

onBeforeMount(() => {
    // invoicesStore.fetchLocalData()
    invoicesStore.fetch();
});

onUnmounted(() => {
    invoicesStore.clear();
});

const FIELDS = ref([
    { label: t("invoices.table.actions"), id: "actions" },
    { label: t("invoices.table.total"), id: "totalPrice" },
    { label: t("invoices.table.status"), id: "status" },
    { label: t("invoices.table.customer"), id: "customerName" },
    { label: t("invoices.table.date"), id: "createdAt" },
    { label: t("invoices.table.invoiceNumber"), id: "id" },
]);

watchEffect(() => {
    FIELDS.value.forEach((item) => {
        switch (item.id) {
            case "actions":
                item.label = t("invoices.table.actions");
                break;
            case "totalPrice":
                item.label = t("invoices.table.total");
                break;
            case "status":
                item.label = t("invoices.table.status");
                break;
            case "customerName":
                item.label = t("invoices.table.customer");
                break;
            case "createdAt":
                item.label = t("invoices.table.date");
                break;
            case "id":
                item.label = t("invoices.table.invoiceNumber");
                break;
            default:
                break;
        }
    });
});

const tableData = computed(() => {
    const newData = filteredInvoices.value.map((invoice) => {
        let data = {
            createdAt: invoice["created_at"],
            customerId: invoice["customer_id"],
            customerName: invoice["customer_name"],
            mobile: invoice["customr"].mobile || "",
            customer: invoice["customr"],
            services: invoice["services"],
            dailyId: invoice["daily_id"],
            id: invoice["id"],
            status: parseInt(invoice["status"]),
            note: invoice["note"] || "",
            discount: parseFloat(invoice["discount"]) || 0,
        };
        let totalPrice = 0;
        invoice["services"].forEach((service) => {
            totalPrice += service.pivot.number_of_piece * service.price;
        });
        data["totalPrice"] = totalPrice;
        data["totalDiscount"] =
            totalPrice * ((parseFloat(data["discount"]) || 0) / 100);
        return data;
    });

    newData.sort((a, b) => {
        if (a[sortColumn.value] > b[sortColumn.value]) {
            return ascending.value ? 1 : -1;
        } else if (a[sortColumn.value] < b[sortColumn.value]) {
            return ascending.value ? -1 : 1;
        }
        return 0;
    });
    return newData;
});

const itemsPaginated = computed(() =>
    // invoices.value.slice(
    tableData.value.slice(
        perPage.value * (currentPage.value - 1),
        perPage.value * currentPage.value
    )
);

const remove = (arr, cb) => {
    const newArr = [];

    arr.forEach((item) => {
        if (!cb(item)) {
            newArr.push(item);
        }
    });

    return newArr;
};

const checked = (isChecked, client) => {
    if (isChecked) {
        checkedRows.value.push(client);
    } else {
        checkedRows.value = remove(
            checkedRows.value,
            (row) => row.id === client.id
        );
    }
};

// a function to sort the table
const sortTable = (field) => {
    if (sortColumn.value === field) {
        ascending.value = !ascending.value;
    } else {
        ascending.value = true;
        if (field != "actions") sortColumn.value = field;
    }
};

// page change
const change = (pageState) => {
    console.log(pageState);
    currentPage.value = pageState.pageNumber;
    perPage.value = pageState.pageSize;
};

const toggleModalActive = () => {
    invoiceModalActive.value = !invoiceModalActive.value;
};
// ** Actions

const handleClickViewInvoicePDF = (invoice) => {
    console.log(invoice);
    invoicesStore.setActiveInvoice(invoice);
    invoiceModalActive.value = true;
};

const handleClickEdit = (invoice) => {
    activeInvoice.value = invoice;
    invoicesStore.setActiveInvoice(invoice);
    editModalActive.value = true;
};

const handleQrcode = (invoice) => {
    console.log("URL", window.location.origin);
    console.log("invoice", invoice);
    qrValue.value = window.location.origin + "/invoice/" + invoice.id;
    activeInvoice.value = invoice;
    invoicesStore.setActiveInvoice(invoice);
    // qrcodeModalActive.value = true
    isQrModalActive.value = true;
};

const downloadPdf = () => {
    console.log(html2Pdf.value);
};
const toggleNoteModalActive = (val) => {
    editModalActive.value = val;
};
const toggleNotqrModalActive = (val) => {
    qrcodeModalActive.value = val;
};

const statusUpdated = (data) => {
    const { id, status } = data;
    console.log(id);
    console.log(status);

    const _invoice = filteredInvoices.value.find(
        (invoice) => parseInt(invoice.id) === id
    );
    console.log(_invoice);
    _invoice.status = status;
    console.log(_invoice);

    // update status value in the current array
};

const handleClickDelete = (invoice) => {
    invoicesStore.setActiveInvoice(invoice);
    isModalDangerActive.value = true;
};

const confirmDelete = () => {
    invoicesStore
        .delete(selectedInvoice.value.id)
        .then((res) => {
            console.log(res);
        })
        .catch((err) => {
            console.log(err);
        })
        .finally(() => {});
};
</script>

<template>
    <card-box-modal
        v-model="isQrModalActive"
        :title="t('invoices.modal.qrcode.title')"
        :button-label="t('common.cancel')"
        button="bg-main"
    >
        <div class="flex justify-center py-5">
            <qrcode-vue :value="qrValue" :size="qrSize" level="H" />
        </div>
    </card-box-modal>
    <UpdateInvoiceStatusModal
        :modal-active="editModalActive"
        :invoice="activeInvoice"
        @toggle-modal-active="toggleNoteModalActive"
        @status-updated="statusUpdated"
    />

    <QrcodeModal
        :modal-active="qrcodeModalActive"
        :invoice="activeInvoice"
        @toggle-modal-active="toggleNotqrModalActive"
        @status-updated="statusUpdated"
    />

    <CardBoxModal v-model="isModalActive" title="Sample modal">
        <p>Lorem ipsum dolor sit amet <b>adipiscing elit</b></p>
        <p>This is sample modal</p>
    </CardBoxModal>

    <CardBoxModal
        v-model="isModalDangerActive"
        :title="t('invoices.modal.delete.title')"
        button="bg-main"
        has-cancel
        @confirm="confirmDelete"
    >
        <p>Are you sure?</p>
    </CardBoxModal>
    <!-- <vue3-html2pdf
      :show-layout="false"
      :float-layout="true"
      :enable-download="true"
      :preview-modal="true"
      :paginate-elements-by-height="1400"
      filename="hee hee"
      :pdf-quality="2"
      :manual-pagination="false"
      pdf-format="a4"
      pdf-orientation="landscape"
      pdf-content-width="800px"

      @progress="onProgress($event)"
      @hasStartedGeneration="hasStartedGeneration()"
      @hasGenerated="hasGenerated($event)"
      ref="html2Pdf"
      >
        <template  v-slot:pdf-content> -->
    <TableInvoiceModal
        v-if="selectedInvoice"
        title=""
        :modal-active="invoiceModalActive"
        @toggle-modal-active="toggleModalActive"
        @download-pdf="downloadPdf"
    />
    <!-- </template>
</vue3-html2pdf> -->
    <div v-if="checkedRows.length" class="p-3 bg-gray-100/50 dark:bg-slate-800">
        <span
            v-for="checkedRow in checkedRows"
            :key="checkedRow.id"
            class="inline-block px-2 py-1 rounded-sm mr-2 text-sm bg-gray-100 dark:bg-slate-700"
        >
            {{ checkedRow.name }}
        </span>
    </div>

    <table>
        <thead>
            <tr>
                <th
                    v-for="field in FIELDS"
                    :key="field.id"
                    @click="sortTable(field.id)"
                    class="cursor-pointer"
                >
                    <BaseIcon
                        v-show="field.id === sortColumn"
                        :path="ascending ? mdiSortAscending : mdiSortDescending"
                        class="text-gray-600"
                    />
                    {{ field.label }}
                </th>
                <th v-if="checkable" />
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="6" v-show="loading">
                    <div class="w-full flex justify-center">
                        <!-- <LoadingBar /> -->
                        <atom-spinner
                            :animation-duration="1000"
                            :size="60"
                            color="#4BC49A"
                        />
                    </div>
                </td>
            </tr>
            <tr v-for="invoice in itemsPaginated" :key="invoice.id">
                <td class="before:hidden whitespace-nowrap">
                    <BaseButtons type="justify-start lg:justify-end" no-wrap>
                        <BaseButton
                            color="brandGreen"
                            :icon="mdiTrashCan"
                            small
                            border="border"
                            borderColor="brandBorder"
                            @click="handleClickDelete(invoice)"
                        />
                        <BaseButton
                            color="white"
                            :icon="mdiFileEyeOutline"
                            small
                            border="border"
                            borderColor="brandBorder"
                            @click="() => handleClickViewInvoicePDF(invoice)"
                        />
                        <BaseButton
                            color="white"
                            :icon="mdiPencil"
                            small
                            border="border"
                            borderColor="brandBorder"
                            @click="() => handleClickEdit(invoice)"
                        />
                        <BaseButton
                            color="white"
                            :icon="mdiQrcode"
                            small
                            border="border"
                            borderColor="brandBorder"
                            @click="() => handleQrcode(invoice)"
                        />
                    </BaseButtons>
                </td>

                <td data-label="Total">
                    {{ invoice.totalPrice }}
                </td>
                <td data-label="Progress" class="">
                    <status-label :status="parseInt(invoice.status)" />
                </td>
                <td data-label="customer">
                    {{ invoice.customerName }}
                </td>
                <td data-label="Created" class="whitespace-nowrap">
                    <small class="text-gray-500 dark:text-slate-400">{{
                        format(new Date(invoice.createdAt), "dd-MM-yyyy HH:mm")
                    }}</small>
                </td>
                <td data-label="InvoiceId">
                    {{ invoice.id }}
                </td>
                <!-- <td class="border-b-0 lg:w-6 before:hidden">
          <UserAvatar
            :username="client.name"
            class="w-24 h-24 mx-auto lg:w-6 lg:h-6"
          />
        </td> -->
                <TableCheckboxCell
                    v-if="checkable"
                    @checked="checked($event, client)"
                />
            </tr>
        </tbody>
    </table>
    <!-- <NewPaginationBar :per-page="perPage" :total="invoices.length" v-model="currentPage" /> -->
    <Page
        v-model="currentPage"
        :total-row="filteredInvoices.length"
        language="en"
        :page-size-menu="[5, 10, 20]"
        @change="change"
        class="my-8"
    />
</template>
