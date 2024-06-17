<script setup>
import SearchIconButton from "../../../components/SearchIconButton.vue";
import BaseInput from "../../../components/BaseInput.vue";
import CloseIcon from "@/assets/icons/close_grey.svg";
import { Form, useForm } from "vee-validate";
import { useInvoicesStore } from "@/store/pinia/invoices";
import { storeToRefs } from "pinia";
import { ref } from "vue";
import { useI18n } from "vue-i18n";

const { t } = useI18n();

const fromDate = ref("");
const toDate = ref("");
const clear = ref(false);
const invoicesStore = useInvoicesStore();
const { invoices, selectedInvoice, loading } = storeToRefs(useInvoicesStore());

const onSubmit = (values) => {
    // Submit to API
    console.log(values);
};

const handleClick = (values) => {
    console.log(values);

    const newData = invoices.value.filter((invoice) => {
        if (values.invoice_number) {
            if (invoice.id != values.invoice_number) {
                return false;
            }
        }

        if (values.from_date) {
            if (compareDates(invoice.created_at, values.from_date) < 0) {
                return false;
            }
        }

        if (values.to_date) {
            if (compareDates(values.to_date, invoice.created_at) < 0) {
                return false;
            }
        }
        return true;
    });
    console.log(newData);
    invoicesStore.setFilteredInvoices(newData);
};

const compareDates = (d1, d2) => {
    let date1 = new Date(d1).getTime();
    let date2 = new Date(d2).getTime();

    if (date1 < date2) {
        return -1;
    } else if (date1 > date2) {
        return 1;
    } else {
        return 1;
    }
};

const handleClickClearSearch = (resetForm) => {
    invoicesStore.setFilteredInvoices(invoices.value);
    toDate.value = "";
    fromDate.value = "";
    clear.value = true;
    resetForm();
    setTimeout(() => {
        clear.value = false;
    }, 500);
};
</script>

<template>
    <Form
        class="w-full flex justify-between items-center"
        @submit="onSubmit"
        v-slot="{ values, handleReset }"
    >
        <div class="flex just-between items-center gap-4">
            <div class="flex items-center">
                <search-icon-button @on-click="() => handleClick(values)">
                </search-icon-button>
            </div>
            <div
                class="flex items-center justify-center px-6 py-3 bg-white rounded-lg border cursor-pointer"
                @click="() => handleClickClearSearch(handleReset)"
            >
                <CloseIcon />
            </div>
        </div>

        <div class="flex items-center gap-6 justify-between">
            <div>
                <label class="flex justify-end text-sm">{{t('invoices.toDate')}}</label>
                <base-input
                    input-type="date"
                    name="to_date"
                    icon-:src="`/images/invoices/calendar_grey.svg`"
                    placeholder="إلى تاريخ"
                    class="hidden sm:flex"
                    :value="toDate"
                    :clear="clear"
                />
            </div>
            <div>
                <label class="flex justify-end text-sm">{{t('invoices.fromDate')}}</label>
                <base-input
                    input-type="date"
                    name="from_date"
                    icon-:src="`/images/invoices/calendar_grey.svg`"
                    placeholder="من تاريخ"
                    class="hidden sm:flex"
                    :value="fromDate"
                    :clear="clear"
                />
            </div>
            <div>
              <label class="flex justify-end text-sm">{{t('invoices.invoiceNumber')}}</label>
              <base-input
                input-type="text"
                name="invoice_number"
                icon-:src="`/images/invoices/invoice_grey.svg`"
                
                class="hidden sm:flex"
            />
            </div>
        </div>
    </Form>
</template>

<style lang="scss" scoped></style>
