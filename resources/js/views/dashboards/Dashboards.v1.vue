<script setup>
import {
    ref,
    onMounted,
    onBeforeMount,
    onBeforeUnmount,
    onUnmounted,
    watchEffect,
} from "vue";
import { useI18n } from "vue-i18n";
import { storeToRefs } from "pinia";

import CardBoxModal from "@/components/CardBoxModal.vue";
import CartContentList from "../components/CartContentList.vue";
import PieceList from "./components/PieceList.vue";
import UserIcon from "@/assets/icons/placeholder_user.svg";
import PhoneIcon from "@/assets/icons/phone.svg";

import { usePiecesStore } from "../../store/pinia/pieces";
import { useCartsStore } from "../../store/pinia/carts";
import { useCustomersStore } from "@/store/pinia/customers";
import ServiceList from "./components/ServiceList.vue";
import { CONTROL_BUTTON_TYPE } from "@/constants.js";
import LoadingBar from "@/components/LoadingBar.vue";
import { useAuthStore } from "@/store/pinia/auth";

const { t } = useI18n();
const userAuthStore = useAuthStore();
const { role, userEmail } = storeToRefs(useAuthStore());
const cartControlArr = [
    {
        icon: "/images/home/discount.svg",
        title: t("dashboard.discount"), //'خصم',
        noBorder: false,
        type: CONTROL_BUTTON_TYPE.ADD_DISCOUNT,
    },
    {
        icon: "/images/home/comments.svg",
        title: t("dashboard.note"), //'ملاحظات',
        noBorder: false,
        type: CONTROL_BUTTON_TYPE.ADD_NOTE,
    },
    {
        icon: "/images/home/delete.svg",
        title: t("dashboard.discard"), //'تجاهل',
        noBorder: false,
        type: CONTROL_BUTTON_TYPE.CANCEL,
    },
    {
        icon: "/images/home/suspension.svg",
        title: t("dashboard.pause"), //'تعليق',
        noBorder: false,
        type: CONTROL_BUTTON_TYPE.HOLD,
    },
];

const phone = ref(null);
const username = ref("");

const modalOneActive = ref(false);

const pieceStore = usePiecesStore();
const { pieces, selectedPiece, pieceSelected, loading } = storeToRefs(
    usePiecesStore()
);

const customerStore = useCustomersStore();
const cartsStore = useCartsStore();

onBeforeMount(() => {
    // pieceStore.fetchLocalData()
    pieceStore.fetch();
    customerStore.fetch();
});
onMounted(() => {
    console.log('Email:',userEmail.value, 'Role:', role.value);
    console.log(pieceStore.pieces);
});

onBeforeUnmount(() => {});

onUnmounted(() => {
    customerStore.clear();
    pieceStore.clear();
    cartsStore.clear();
});

watchEffect(() => {
    cartControlArr.forEach((item) => {
        switch (item.type) {
            case CONTROL_BUTTON_TYPE.ADD_DISCOUNT:
                item.title = t("dashboard.discount");
                break;
            case CONTROL_BUTTON_TYPE.ADD_NOTE:
                item.title = t("dashboard.note");
                break;
            case CONTROL_BUTTON_TYPE.CANCEL:
                item.title = t("dashboard.discard");
                break;
            case CONTROL_BUTTON_TYPE.HOLD:
                item.title = t("dashboard.pause");
                break;
            default:
                break;
        }
    });
});

const fetchPieceList = () => {
    // pieceService.getList().then((res) => {
    //     console.log(res)
    // })
};

const setActivePiece = (piece) => {
    pieceStore.setSelectedPiece(piece);
};

const setActiveService = (service) => {};

const createCustomer = () => {
    if (phone.value.length) {
        customerStore.addNewCustomer({
            name: username.value,
            mobile: phone.value.replace(/\s/g, ""),
        });
    } else {
        console.log("phone number required");
    }
    username.value = "";
    phone.value = "";
};

const cancelCustomerModal = () => {
    username.value = "";
    phone.value = "";
};

const sanitizeInput = () => {
  // Filter out non-numeric characters from the phone number
  phone.value = phone.value.replace(/\D/g, '');
};  
</script>

<template>
    <card-box-modal
        v-model="modalOneActive"
        :title="t('dashboard.addCustomer')"
        :button-label="t('common.save')"
        button="bg-main"
        @confirm="createCustomer"
        @cancel="cancelCustomerModal"
    >
        <div
            class="w-full px-4 py-3 border border-gray-100 flex justify-end items-center"
        >
            <input
                class="w-full focus:outline-none text-right"
                type="text"
                placeholder="أحمد أحمد"
                v-model="username"
            />
            <UserIcon class="ml-2" />
        </div>
        <div
            class="w-full px-4 py-3 border border-gray-100 flex justify-end items-center"
        >
            <vue-tel-input v-model="phone" @input="sanitizeInput"></vue-tel-input>
            <PhoneIcon class="ml-2" />
        </div>
    </card-box-modal>
    <div class="container mx-auto">
        <!-- <Breadcrumbs parentTitle="Dashboard" subParentTitle="Dashboard v1" /> -->
        <div class="grid grid-cols-12 gap-5">
            <div class="col-span-12 xl:col-span-4 md:col-span-6 lg:col-span-5">
                <FullHeightCard>
                    <cart-content-list
                        :button-array="cartControlArr"
                        v-on:show-modal="modalOneActive = true"
                    />
                </FullHeightCard>
            </div>
            <div class="col-span-12 xl:col-span-8 md:col-span-6 lg:col-span-7">
                <div class="flex justify-end font-readex">
                    {{ t("dashboard.pieceType") }}
                </div>
                <template v-if="loading">
                    <div class="flex justify-center items-center">
                        <LoadingBar />
                    </div>
                </template>
                <template v-else>
                    <PieceList
                        :pieces="pieces"
                        @set-active-piece="setActivePiece"
                    />
                </template>
                <div class="w-full mt-7 services-wrapper" v-if="selectedPiece">
                    <div class="flex justify-end service-title">{{ t("dashboard.serviceType") }}</div>
                    <ServiceList :services="selectedPiece?.services" />
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
@import "vue-select/dist/vue-select.css";

.control-wrapper {
    background: #f1f7fe;
    border: 1px solid #ecf1f4;
    padding: 0.75rem 0;
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

.widget-type-label {
    font-family: "Shubbak-Medium";
    font-style: normal;
    font-weight: 500;
    font-size: 18px;
    line-height: 25px;
    /* identical to box height */

    text-align: center;

    /* Neuturals/Medium Dark */

    color: #484860;
}

.selected-image {
    -webkit-filter: invert(55%) sepia(50%) saturate(460%) hue-rotate(149deg)
        brightness(97%) contrast(99%);
    filter: invert(55%) sepia(50%) saturate(460%) hue-rotate(149deg)
        brightness(97%) contrast(99%);
}

.widget-label {
    font-family: "Readex Pro";
    font-style: normal;
    font-weight: 400;
    font-size: 16px;
    line-height: 20px;
    text-align: center;

    /* Neuturals/Medium Dark */

    color: #484860;
}

.services-wrapper {
    transition: all 0.4s ease-in-out;

    .service-title {
        font-family: "Readex Pro";
        font-style: normal;
        font-weight: 400;
        font-size: 16px;
        line-height: 20px;
        /* identical to box height */

        text-align: center;

        /* Neuturals/Medium Dark */

        color: #484860;
    }
}

.focus-shadow-none {
    &:focus-within {
        box-shadow: none;
        border: none;
    }
}
</style>
