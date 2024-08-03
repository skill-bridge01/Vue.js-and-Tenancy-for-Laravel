<script setup>
import { computed, onMounted, onUnmounted, ref, watch } from "vue";
import CardBoxModal from "@/components/CardBoxModal.vue";
import { useInvoicesStore } from "@/store/pinia/invoices";
import { storeToRefs } from "pinia";
import { INVOICE_STATUS } from "@/constants.js";
import { useI18n } from "vue-i18n";

const props = defineProps({
    modalActive: Boolean,
    invoice: Object,
});

const { t } = useI18n();

const emits = defineEmits(["toggleModalActive", "statusUpdated"]);

onUnmounted(() => {
    console.log("unmounted");
});
const invoicesStore = useInvoicesStore();
const { selectedInvoice } = storeToRefs(useInvoicesStore());
const selected = ref(0);
const firstTime = ref(true);

const status = [
    { label: t("invoices.modal.edit.inProgress"), id: 1 },
    { label: t("invoices.modal.edit.readyPickup"), id: 2 },
    { label: t("invoices.modal.edit.delivered"), id: 3 },
];

const active = computed({
    get() {
        return props.modalActive;
    },
    set(val) {
        emits("toggleModalActive", val);
    },
});

watch(props, () => {
    // if (firstTime.value) {
    selected.value = props.invoice.status;
    // firstTime.value = false
    // }
});

const confirm = () => {
    console.log(props.invoice);
    console.log(selected.value);
    invoicesStore
        .updateInvoice({
            id: selectedInvoice.value.id,
            status: selected.value,
        })
        .then((res) => {
            console.log(res);
            emits("statusUpdated", {
                id: selectedInvoice.value.id,
                status: res.status,
            });
        });
};

const cancel = () => {
    console.log("canceled");
};
</script>

<!-- title="ملاحظات" -->
<!-- button-label="حفظ وإغلاق" -->
<template>
    <card-box-modal
        v-model="active"
        :title="t('invoices.modal.edit.title')"
        :button-label="t('invoices.modal.edit.update')"
        button="bg-main"
        @confirm="confirm"
        @cancel="cancel"
    >
        <div class="w-full px-4 py-3 flex justify-center items-center">
            <select v-model="selected" class="border border-gray-100 h-12 w-80">
                <option disabled value="">
                    {{ t("invoices.modal.edit.select") }}
                </option>
                <option v-for="s in status" :key="s.id" :value="s.id">
                    {{ s.label }}
                </option>
            </select>
        </div>
    </card-box-modal>
</template>

<style lang="scss" scoped>
@import "vue-select/dist/vue-select.css";
</style>
