<script setup>
import CartItem from "../dashboards/components/CartItem.vue";
import CartButton from "./CartButton.vue";
import CustomerList from "../dashboards/components/CustomerList.vue";
import { ref, computed, onMounted } from "vue";
import { useI18n } from "vue-i18n";

import { useCartsStore } from "@/store/pinia/carts";
import { usePiecesStore } from "../../store/pinia/pieces";
import { useCustomersStore } from "@/store/pinia/customers";
import { useInvoicesStore } from "@/store/pinia/invoices";
import { storeToRefs } from "pinia";
import InvoiceModal from "../../components/InvoiceModal.vue";
import { SpringSpinner } from "epic-spinners";
import CustomerAutocomplete from "../../components/CustomerAutocomplete.vue";
import { useRoute, useRouter } from "vue-router";
import BaseSelect from "../../components/BaseSelect.vue";

const { t } = useI18n();
const customersStore = useCustomersStore();
const { customers, selectedCustomer } = storeToRefs(useCustomersStore());
const cartsStore = useCartsStore();
const { carts, selectedCart, note, discount } = storeToRefs(useCartsStore());
const invoicesStore = useInvoicesStore();
const pieceStore = usePiecesStore();

const loading = ref(false);
const selectedOption = ref("card");
onMounted(() => {
    // viewInvoices()
});

const props = defineProps({
    buttonArray: Array,
    modalOneActive: Boolean,
});

const emits = defineEmits(["showModal"]);
const route = useRoute();
const router = useRouter();
const invoiceModalActive = ref(false);
const invoiceData = ref(null);
const newCarts = ref([]);
const newCartss = ref('');

const handleSelectedOption = (option) => {
    selectedOption.value = option;
};

const totalPrice = computed(() => {
    let total = 0;
    carts.value.forEach((cart) => {
        total += cart.service.pivot.price * cart.count;
    });

    return total;
});

const handleClick = () => {
    emits("showModal");
};

const handleClickCustomerList = (customer) => {
    customersStore.setActiveCustomer(customer);
};

const handleRemoveCustomer = () => {
    customersStore.setActiveCustomer(null);
};

const handleEditCustomer = () => {};

const handleChange = () => {};

const handleClickCreateInvoice = () => {
    console.log('carts', carts.value)
    if (carts.value.length && selectedCustomer.value) {
        if (loading.value) return;
        loading.value = true;
        invoicesStore
            .createInvoice(
                carts.value,
                note.value,
                discount.value,
                selectedCustomer.value,
                selectedOption.value
            )
            .then((res) => {
                console.log('RES', res)
                console.log('CARTS', carts.value)
                invoiceData.value = res;
                newCarts.value = carts.value;
                newCartss.value = 'THKNKS';
                invoiceModalActive.value = true;
                cartsStore.clear()
                customersStore.clear()
                pieceStore.clear()
            })
            .catch((err) => {
                console.log(err);
            })
            .finally(() => {
                loading.value = false;
            });
    } else {
        alert("select customer");
    }
};

const toggleModalActive = () => {
    invoiceModalActive.value = !invoiceModalActive.value;
};

const handleClickRemoveDiscount = () => {
    cartsStore.updateDiscount(0);
};

const handleClickDeleteNote = () => {
    cartsStore.updateNote("");
};

const viewInvoices = () => {
    setTimeout(() => {
        router
            .push({ name: "InvoiceList" })
            .then((res) => {
                console.log(res);
            })
            .catch((err) => {
                console.log(err);
            });
    }, 1000);
};
// const options = ref([
const options = computed(() => [
    {
        label: t('dashboard.card'),
        value: "card",
    },
    {
        label: t('dashboard.cash'),
        value: "cash",
    },
]);
</script>

<template>
    <InvoiceModal
        title=""
        :modal-active="invoiceModalActive"
        :new-carts="newCarts"
        :new-cartss="newCartss"
        :invoice-data="invoiceData"
        @toggle-modal-active="toggleModalActive"
        @view-invoices="viewInvoices"
    />
    <div class="flex flex-col justify-between h-full">
        <div>
            <!-- Control list : -- Start -- :  discount, notes, cancel, hold -->
            <div class="flex justify-between items-center control-wrapper">
                <cart-button
                    v-for="(button, index) in props.buttonArray"
                    :title="button.title"
                    :image="button.icon"
                    :key="index"
                    :type="button.type"
                    :no-bordder="
                        index === props.buttonArray?.length - 1 ? true : false
                    "
                />
            </div>
            <div
                v-if="!selectedCustomer"
                class="flex justify-between items-center px-6 py-3 customer-wrapper"
            >
                <button type="button" @click="handleClick">
                    <img class="" :src="`/images/home/plus.svg`" alt="" />
                </button>

                <div class="flex justify-between items-center">
                    <CustomerAutocomplete
                        source="search"
                        label="name"
                        @click-customer-row="handleClickCustomerList"
                    />
                </div>
            </div>
            <!-- end -- Add customers-->

            <!-- start -- Customer List-->
            <CustomerList
                v-if="selectedCustomer"
                :customer="selectedCustomer"
                @handle-remove-customer="handleRemoveCustomer"
                @handle-edit-customer="handleEditCustomer"
            />
            <!-- end -- Customer List-->
            <!-- Cart Form-->
            <div
                v-if="carts.length > 0"
                class="overflow-x-hidden overflow-y-scroll max-h-[40vh]"
            >
                <cart-item
                    v-for="(cart, index) in carts"
                    :key="index"
                    :cart="cart"
                />
            </div>
            <!-- end - Cart form -->
        </div>
        <!-- start -- bottom buttons -->
        <div class="relative flex flex-col justify-center">
            <div class="w-full p-4 bottom-4">
                <div
                    v-if="discount"
                    class="flex justify-between items-center px-6 py-3 mb-3 discount-wrapper"
                >
                    <button type="button" @click="handleClickRemoveDiscount">
                        <img
                            class=""
                            :src="`/images/home/trash_grey.svg`"
                            alt=""
                        />
                    </button>
                    <div class="flex justify-between items-center">
                        <span class="customer-label"
                            >{{ t("dashboard.discount") }}
                            {{ discount }} ريال</span
                        >
                    </div>
                </div>
                <div
                    v-if="note.length > 0"
                    class="flex justify-between items-center px-6 py-3 note-wrapper"
                >
                    <button type="button" @click="handleClickDeleteNote">
                        <img
                            class=""
                            :src="`/images/home/close_grey.svg`"
                            alt=""
                        />
                    </button>
                    <div class="flex justify-between items-center">
                        <span class="customer-label">{{
                            note
                        }}</span>
                    </div>
                </div>
                <div class="py-4">
                    <base-select
                        :label="t('dashboard.selectPayment')"   
                        :options="options"
                        @update:selected="handleSelectedOption"
                    />
                </div>
                <div
                    class="create-invoice-button flex items-center py-4 justify-center"
                    :class="
                        carts.length > 0 && selectedCustomer && !loading
                            ? 'enabled cursor-pointer'
                            : 'disabled'
                    "
                    @click="
                        carts.length > 0 && selectedCustomer 
                            ? handleClickCreateInvoice()
                            : ''
                    "
                >
                    <span v-if="carts.length === 0">
                        {{ t("dashboard.chooseWidget") }}
                    </span>
                    <div
                        v-else
                        class="w-full flex justify-between items-center px-4"
                    >
                        <div class="flex justify-between">
                            <span class="mr-3">ر.س</span>
                            <span>{{ totalPrice }}</span>
                        </div>
                        <span>
                            {{ t("dashboard.createInvoice") }}
                        </span>
                        <spring-spinner
                            v-show="loading"
                            :animation-duration="2500"
                            :size="25"
                            color="#fff"
                        />
                    </div>
                </div>
            </div>
        </div>
        <!-- end -- bottom buttons -->
    </div>
</template>

<style lang="scss" scoped>
.control-wrapper {
    background: #f1f7fe;
    border: 1px solid #ecf1f4;
    padding: 0.75rem 0;
}

.customer-wrapper {
    border-bottom: 1px solid #ecf1f4;
}

.customer-label {
    font-family: "Readex Pro";
    font-style: normal;
    font-weight: 300;
    font-size: 16px;
    line-height: 20px;
    /* identical to box height */

    text-align: center;

    /* Primary/DarkBlue-04 */

    color: #a1aaba;
}

.create-invoice-button {
    &.enabled {
        @apply bg-brandGreen;
    }

    &.disabled {
        background: #c0c3c8;
    }

    border-radius: 8px;
    // width: calc(100% - 32px);

    font-family: "Readex Pro";
    font-style: normal;
    font-weight: 500;
    font-size: 16px;
    line-height: 20px;
    /* identical to box height */

    color: #ffffff;

    .name-label {
        font-family: "Readex Pro";
        font-style: normal;
        font-weight: 300;
        font-size: 16px;
        line-height: 20px;
        /* identical to box height */

        text-align: center;

        /* Neuturals/Medium Dark */

        color: #484860;
    }
}

.note-wrapper {
    background: #f1f7fe;
    border: 1px solid #daf3fe;
    border-radius: 8px;
    margin-bottom: 12px;
}

.discount-wrapper {
    background: #fefbf3;
    border: 1px solid #ffe7a6;
    border-radius: 8px;
}

.hide-scrollbar {
    -ms-overflow-style: none;
    /* Internet Explorer 10+ */
    scrollbar-width: none;
    /* Firefox */
}

.hide-scrollbar::-webkit-scrollbar {
    display: none;
    /* Safari and Chrome */
}
</style>
