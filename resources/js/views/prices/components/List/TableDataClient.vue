<script setup>
import { computed, ref, onBeforeMount, onUnmounted, watchEffect } from "vue";
import { useI18n } from "vue-i18n";
import * as Yup from "yup";
import { Form } from "vee-validate";
import { mdiPlus, mdiTrashCan, mdiPencil, mdiFileEyeOutline } from "@mdi/js";
import CardBoxModal from "@/components/CardBoxModal.vue";
import TableCheckboxCell from "@/components/TableCheckboxCell.vue";
import BaseIcon from "@/components/BaseIcon.vue";
import BaseButtons from "@/components/BaseButtons.vue";
import BaseButton from "@/components/BaseButton.vue";
import BaseInput from "@/components/BaseInput.vue";
import { format } from "date-fns";
import { Page } from "v-page";
// Importing the lodash library
import { mdiSortAscending, mdiSortDescending } from "@mdi/js";
import { usePricesStore } from "@/store/pinia/prices";
import { usePiecesStore } from "@/store/pinia/pieces";
import { useServicesStore } from "@/store/pinia/services";
import { storeToRefs } from "pinia";
import { AtomSpinner } from "epic-spinners";

const props = defineProps({
    checkable: Boolean,
    setModalActive: {
        type: Boolean,
        default: false,
    },
});

const schema = Yup.object().shape({
    price: Yup.string().min(1).required(),
    piece: Yup.string().min(1).required(),
    service: Yup.string().min(1).required(),
});

const emits = defineEmits({});
const isNewModalActive = ref(false);
const showNewModal = () => {
    document.getElementById("NewForm").reset();
    clearData();
    isNewModalActive.value = true;
};

defineExpose({
    showNewModal,
});

const { t } = useI18n();
const editModalActive = ref(false);
const pricesStore = usePricesStore();
const { prices, selectedPrice, loading } = storeToRefs(usePricesStore());
const piecesStore = usePiecesStore();
const { pieces } = storeToRefs(usePiecesStore());
const servicesStore = useServicesStore();
const { services } = storeToRefs(useServicesStore());
const isModalDangerActive = ref(false);
const perPage = ref(5);
const currentPage = ref(1);
const checkedRows = ref([]);
// a value to check for sort
const sort = ref(false);
const ascending = ref(false);
const sortColumn = ref("");
const errorMessage = ref(null);
const successMessage = ref(null);
const selectedOption = ref("");

// new piece
const newTitle = ref("");
const selectedService = ref("");
const selectedPiece = ref("");

onBeforeMount(() => {
    pricesStore.fetch();
    piecesStore.fetch();
    servicesStore.fetch();
});

onUnmounted(() => {
    pricesStore.clear();
});

const FIELDS = ref([
    { label: t("prices.table.actions"), id: "actions" },
    { label: t("prices.table.date"), id: "createdAt" },
    { label: t("prices.table.service"), id: "service" },
    { label: t("prices.table.piece"), id: "piece" },
    { label: t("prices.table.price"), id: "title" },
    { label: t("prices.table.priceNumber"), id: "id" },
]);

watchEffect(() => {
    FIELDS.value.forEach((item) => {
        switch (item.id) {
            case "actions":
                item.label = t("prices.table.actions");
                break;
            case "createdAt":
                item.label = t("prices.table.date");
                break;
            case "service":
                item.label = t("prices.table.service");
                break;
            case "piece":
                item.label = t("prices.table.piece");
                break;
            case "title":
                item.label = t("prices.table.price");
                break;
            case "id":
                item.label = t("prices.table.priceNumber");
                break;
            default:
                break;
        }
    });
});

const tableData = computed(() => {
    const newData = prices.value.map((price) => {
        let data = {
            createdAt: price["created_at"],
            service: price.serviceinfo.services_title,
            piece: price.pieceinfo.piece_title,
            title: price["price"],
            id: price["id"],
            serviceId: price.serviceinfo.id,
            pieceId: price.pieceinfo.id,
        };
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

// ** Actions
const handleClickEdit = (price) => {
    pricesStore.setSelectedPrice(price);
    selectedService.value = price.serviceId;
    selectedPiece.value = price.pieceId;
    newTitle.value = String(price.title);
    document.getElementById("EditForm").reset();
    editModalActive.value = true;
};

const handleClickDelete = (price) => {
    console.log("price", price);
    pricesStore.setSelectedPrice(price);
    isModalDangerActive.value = true;
};

const handleChangePieceTitle = (event) => {
    newTitle.value = event.target.value;
};

const confirm = () => {
    pricesStore
        .create(selectedService.value, selectedPiece.value, newTitle.value)
        .then((res) => {
            if (res.error) {
                console.log("PriceCreateError", res.error);
                errorMessage.value = t("prices.modal.existError");
                setTimeout(() => {
                    selectedService.value = "";
                    selectedPiece.value = "";
                    document.getElementById("NewForm").reset();
                    newTitle.value = "";
                    errorMessage.value = null;
                }, 1500);
            } else if (res.valueError) {
                console.log("PriceCreateError", res.error);
                errorMessage.value = t("prices.modal.valueError");
                setTimeout(() => {
                    selectedService.value = "";
                    selectedPiece.value = "";
                    document.getElementById("NewForm").reset();
                    newTitle.value = "";
                    errorMessage.value = null;
                }, 1500);
            } else if (res.success) {
                newTitle.value = "";
                selectedService.value = "";
                selectedPiece.value = "";
                document.getElementById("NewForm").reset();
                successMessage.value = t("prices.modal.create.success");
                setTimeout(() => {
                    successMessage.value = null;
                    isNewModalActive.value = false;
                }, 1500);
            }
        })
        .catch((err) => {
            console.log(err);
        })
        .finally(() => {
            selectedService.value = "";
            selectedPiece.value = "";
            newTitle.value = "";
        });
};

const confirmEdit = () => {
    console.log(selectedPrice.value);
    pricesStore
        .edit(
            selectedPrice.value.id,
            selectedService.value,
            selectedPiece.value,
            newTitle.value
        )
        .then((res) => {
            if (res.error) {
                console.log("PriceCreateError", res.error);
                errorMessage.value = t("prices.modal.existError");
                selectedService.value = "";
                selectedPiece.value = "";
                document.getElementById("EditForm").reset();
                newTitle.value = "";
                setTimeout(() => {
                    errorMessage.value = null;
                }, 1500);
            } else if (res.valueError) {
                console.log("PriceCreateError", res.error);
                errorMessage.value = t("prices.modal.valueError");
                selectedService.value = "";
                selectedPiece.value = "";
                document.getElementById("EditForm").reset();
                newTitle.value = "";
                setTimeout(() => {
                    errorMessage.value = null;
                }, 1500);
            } else if (res.success) {
                // newTitle.value = "";
                selectedService.value = "";
                selectedPiece.value = "";
                document.getElementById("EditForm").reset();
                successMessage.value = t("prices.modal.edit.success");
                
                setTimeout(() => {
                    editModalActive.value = false;
                    successMessage.value = null;
                    editModalActive.value = false;
                }, 1500);
            }
        })
        .catch((err) => {
            console.log(err);
        })
        .finally(() => {
            selectedService.value = "";
            selectedPiece.value = "";
            newTitle.value = "";
        });
};

const confirmDelete = () => {
    console.log("deleteId", selectedPrice.value.id);
    pricesStore
        .delete(selectedPrice.value.id)
        .then((res) => {
            console.log(res);
        })
        .catch((err) => {
            console.log(err);
        })
        .finally(() => {});
};

const clearData = () => {
    selectedPiece.value = "";
    selectedService.value = "";
    selectedPrice.value = null;
    newTitle.value = "";
};
</script>

<template>
    <card-box-modal
        v-model="isNewModalActive"
        :title="t('prices.modal.create.title')"
        :button-label="t('common.save')"
        button="bg-main"
        @confirm="confirm"
        has-cancel
        :showModal="true"
    >
        <Form :validation-schema="schema" id="NewForm">
            <div>
                <label
                    for="countries"
                    class="block mt-10 mb-1 text-xs font-semibold text-gray-600 dark:text-white"
                    >{{ t("prices.table.service") }}</label
                >
                <select
                    id="countries"
                    name="service"
                    v-model="selectedService"
                    @change="handleSelectionChange"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                >
                    <option v-for="service in services" :value="service.id">
                        {{ service.services_title }}
                    </option>
                </select>
            </div>
            <div>
                <label
                    for="countries"
                    class="block mt-3 mb-1 text-xs font-semibold text-gray-600 dark:text-white"
                    >{{ t("prices.table.piece") }}</label
                >
                <select
                    id="countries"
                    v-model="selectedPiece"
                    name="piece"
                    @change="handleSelectionChange"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                >
                    <option v-for="piece in pieces" :value="piece.id">
                        {{ piece.piece_title }}
                    </option>
                </select>
            </div>
            <label
                for="countries"
                class="block mt-3 mb-1 text-xs font-semibold text-gray-600 dark:text-white"
                >{{ t("prices.table.price") }}</label
            >
            <base-input
                input-type="number"
                name="price"
                @input="handleChangePieceTitle"
                :value="newTitle"
                :placeholder="t('prices.modal.create.placeholder')"
                class="border border-gray-300 rounded-lg w-full mb-3 font-readex text-base font-light focus:outline-none"
            />
            <div v-if="errorMessage" class="text-right">
                <p class="text-red-500 text-sm">{{ errorMessage }}</p>
            </div>
            <div v-if="successMessage" class="text-right">
                <p class="text-green-500 text-sm">{{ successMessage }}</p>
            </div>
        </Form>
    </card-box-modal>

    <card-box-modal
        v-model="editModalActive"
        :title="t('prices.modal.edit.title')"
        button="bg-main"
        :button-label="t('common.update')"
        @confirm="confirmEdit"
        has-cancel
        :showModal="true"
    >
        <Form :validation-schema="schema" id="EditForm">
            <div>
                <label
                    for="countries"
                    class="block mt-10 mb-1 text-xs font-semibold text-gray-600 dark:text-white"
                    >{{ t("prices.table.service") }}</label
                >
                <select
                    id="countries"
                    v-model="selectedService"
                    @change="handleSelectionChange"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                >
                    <!-- <option>Service</option> -->
                    <option v-for="service in services" :value="service.id">
                        {{ service.services_title }}
                    </option>
                </select>
            </div>
            <div>
                <label
                    for="countries"
                    class="block mt-3 mb-1 text-xs font-semibold text-gray-600 dark:text-white"
                    >{{ t("prices.table.piece") }}</label
                >
                <select
                    id="countries"
                    v-model="selectedPiece"
                    @change="handleSelectionChange"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                >
                    <option v-for="piece in pieces" :value="piece.id">
                        {{ piece.piece_title }}
                    </option>
                </select>
            </div>
            <label
                class="block mb-1 mt-3 text-xs font-semibold text-gray-600 dark:text-white"
                >{{ t("prices.table.price") }}</label
            >
            <div class="border border-gray-300 rounded-lg mb-3">
                <input
                    type="number"
                    name="price"
                    placeholder="Price"
                    class="w-full px-1 py-1 rounded-lg text-right focus:outline-none h-9"
                    :value="selectedPrice?.title"
                    @input="handleChangePieceTitle"
                />
            </div>
            <div v-if="errorMessage" class="text-right">
                <p class="text-red-500 text-sm">{{ errorMessage }}</p>
            </div>
            <div v-if="successMessage" class="text-right">
                <p class="text-green-500 text-sm">{{ successMessage }}</p>
            </div>
        </Form>
    </card-box-modal>
    <card-box-modal
        v-model="isModalDangerActive"
        :title="t('prices.modal.delete.title')"
        :button-label="t('common.delete')"
        button="bg-main"
        has-cancel
        @confirm="confirmDelete"
    >
        <p>{{ t("users.modal.delete.confirm") }}</p>
    </card-box-modal>
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
            <tr v-for="piece in itemsPaginated" :key="piece.id">
                <td class="before:hidden whitespace-nowrap">
                    <BaseButtons type="justify-start lg:justify-end" no-wrap>
                        <BaseButton
                            color="brandGreen"
                            :icon="mdiTrashCan"
                            small
                            border="border"
                            borderColor="brandBorder"
                            @click="handleClickDelete(piece)"
                        />
                        <BaseButton
                            color="white"
                            :icon="mdiPencil"
                            small
                            border="border"
                            borderColor="brandBorder"
                            @click="() => handleClickEdit(piece)"
                        />
                    </BaseButtons>
                </td>
                <td :data-label="t('prices.table.date')" class="whitespace-nowrap">
                    <small class="text-gray-500 dark:text-slate-400">{{
                        format(new Date(piece.createdAt), "dd-MM-yyyy HH:mm")
                    }}</small>
                </td>
                <td :data-label="t('prices.table.service')">
                    {{ piece.service }}
                </td>
                <td :data-label="t('prices.table.piece')">
                    {{ piece.piece }}
                </td>
                <td :data-label="t('prices.table.price')">
                    {{ piece.title }}
                </td>
                <td :data-label="t('prices.table.priceNumber')">
                    {{ piece.id }}
                </td>
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
        :total-row="tableData.length"
        language="en"
        :page-size-menu="[5, 10, 20]"
        @change="change"
        class="my-8"
    />
</template>
