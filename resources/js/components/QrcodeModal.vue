<script setup>
import { useI18n } from "vue-i18n";
import QrcodeVue from "qrcode.vue";
import { computed, onMounted, onUnmounted, ref, watch } from "vue";
import CardBoxModal from "@/components/CardBoxModal.vue";
import { useInvoicesStore } from "@/store/pinia/invoices";
import { storeToRefs } from "pinia";
import { INVOICE_STATUS } from "@/constants.js";

const props = defineProps({
    modalActive: Boolean,
    invoice: Object,
});

const { t } = useI18n();

const emits = defineEmits(["toggleNotqrModalActive", "statusUpdated"]);

onUnmounted(() => {
    console.log("unmounted");
});
const invoicesStore = useInvoicesStore();
const { selectedInvoice } = storeToRefs(useInvoicesStore());
const selected = ref(0);
const firstTime = ref(true);
const value = ref("laundry.sass.test:8000");
const size = ref(300);

const status = [
    { label: "In Progress", id: 1 },
    { label: "Ready To Pick up", id: 2 },
    { label: "Delivered", id: 3 },
];

const active = computed({
    get() {
        return props.modalActive;
    },
    set(val) {
        emits("toggleNotqrModalActive", val);
    },
});

watch(props, () => {
    selected.value = props.invoice.status;
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
    toggleNotqrModalActive.value = false;
};
</script>

<template>
    <card-box-modal
        v-model="active"
        :title="t('invoices.modal.qrcode.title')"
        :button-label="t('common.save')"
        button="bg-main"
        @cancel="cancel"
    >
        <div class="w-full px-4 py-3 flex justify-center items-center">
            <qrcode-vue :value="value" :size="size" level="H" />
        </div>
    </card-box-modal>
</template>

<style lang="scss" scoped>
@import "vue-select/dist/vue-select.css";
</style>
