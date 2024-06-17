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
import * as Yup from "yup";
import { Form } from "vee-validate";
import { mdiPlus, mdiTrashCan, mdiPencil, mdiFileEyeOutline } from "@mdi/js";
import CardBoxModal from "@/components/CardBoxModal.vue";
import TableCheckboxCell from "@/components/TableCheckboxCell.vue";
import BaseIcon from "@/components/BaseIcon.vue";
import BaseInput from "@/components/BaseInput.vue";
import BaseButtons from "@/components/BaseButtons.vue";
import BaseButton from "@/components/BaseButton.vue";
import { format } from "date-fns";
import { Page } from "v-page";
// Importing the lodash library
import { mdiSortAscending, mdiSortDescending } from "@mdi/js";

import { usePiecesStore } from "@/store/pinia/pieces";

import { storeToRefs } from "pinia";
import { AtomSpinner } from "epic-spinners";

const props = defineProps({
    checkable: Boolean,
    setModalActive: {
        type: Boolean,
        default: false,
    },
});

const emits = defineEmits({});
const isNewModalActive = ref(false);
const showNewModal = () => {
    document.getElementById("NewForm").reset();
    isNewModalActive.value = true;
};

defineExpose({
    showNewModal,
});

const { t } = useI18n();

const editModalActive = ref(false);

const piecesStore = usePiecesStore();
const { pieces, selectedPiece, loading } = storeToRefs(usePiecesStore());

const isModalDangerActive = ref(false);

const perPage = ref(5);

const currentPage = ref(1);

const checkedRows = ref([]);
// a value to check for sort
const sort = ref(false);
const ascending = ref(false);
const sortColumn = ref("");
const updatedList = ref([]);
const errorMessage = ref(null);
const successMessage = ref(null);

// new piece
const newTitle = ref("");

onBeforeMount(() => {
    // invoicesStore.fetchLocalData()
    piecesStore.fetch();
    console.log("pieces", pieces);
});

onUnmounted(() => {
    piecesStore.clear();
});

const FIELDS = ref([
    { label: t("pieces.table.actions"), id: "actions" },
    { label: t("pieces.table.date"), id: "createdAt" },
    { label: t("pieces.table.piece"), id: "title" },
    { label: t("pieces.table.pieceNumber"), id: "id" },
]);

watchEffect(() => {
    FIELDS.value.forEach((item) => {
        switch (item.id) {
            case "actions":
                item.label = t("pieces.table.actions");
                break;
            case "createdAt":
                item.label = t("pieces.table.date");
                break;
            case "title":
                item.label = t("pieces.table.piece");
                break;
            case "id":
                item.label = t("pieces.table.pieceNumber");
                break;
            default:
                break;
        }
    });
});

const schema = Yup.object().shape({
    piece: Yup.string().min(1).required(),
    // email: Yup.string().email().required(),
    password: Yup.string().min(8).required(),
    password_confirmation: Yup.string()
        .required()
        .oneOf([Yup.ref("password")], "Passwords do not match"),
});

const tableData = computed(() => {
    const newData = pieces.value.map((piece) => {
        let data = {
            createdAt: piece["created_at"],
            title: piece["piece_title"],
            services: piece["services"],
            id: piece["id"],
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
const handleClickEdit = (piece) => {
    document.getElementById("EditForm").reset();
    piecesStore.setSelectedPiece(piece);
    newTitle.value = piece.title;
    editModalActive.value = true;
};

const handleClickDelete = (piece) => {
    piecesStore.setSelectedPiece(piece);
    isModalDangerActive.value = true;
};

const handleChangePieceTitle = (event) => {
    newTitle.value = event.target.value;
};

const confirm = () => {
    piecesStore
        .create(newTitle.value)
        .then((res) => {
            // if (res) {
            //     newTitle.value = "";
            // }
            if (res.error) {
                console.log("PieceCreateError", res.error);
                errorMessage.value = res.error;
                document.getElementById("NewForm").reset();
                newTitle.value = "";
                setTimeout(() => {
                    errorMessage.value = null;
                    
                }, 500);
            } else if (res.success) {
                newTitle.value = "";
                successMessage.value = res.message;
                document.getElementById("NewForm").reset();
                setTimeout(() => {
                    successMessage.value = null;
                    isNewModalActive.value = false;
                }, 500);
            }
        })
        .catch((err) => {
            console.log(err);
        })
        .finally(() => {
            newTitle.value = "";
        });
};

const confirmEdit = () => {
    console.log(selectedPiece.value);
    piecesStore
        .edit(selectedPiece.value.id, newTitle.value)
        .then((res) => {
            console.log("res", res)
            if (res.error) {
                console.log("errorExcution", res.error, res);
                errorMessage.value = res.error;
                document.getElementById("EditForm").reset();
                setTimeout(() => {
                    errorMessage.value = null;
                    newTitle.value = "";
                }, 3000);
            } else if (res.success) {
                newTitle.value = "";
                successMessage.value = res.message;
                editModalActive.value = false;
                document.getElementById("EditForm").reset();
                setTimeout(() => {
                    successMessage.value = null;
                }, 3000);
            }
        })
        .catch((err) => {
            console.log(err);
        })
        .finally(() => {
            newTitle.value = "";
        });
};

const confirmDelete = () => {
    piecesStore
        .delete(selectedPiece.value.id)
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
        v-model="isNewModalActive"
        :title="t('pieces.modal.create.title')"
        :button-label="t('common.save')"
        button="bg-main"
        has-cancel
        @confirm="confirm"
        :showModal="true"
    >
        <Form :validation-schema="schema" id="NewForm">
            <!-- <div class="border border-gray-400 rounded-lg mx-10">
                <input
                    type="text"
                    name="piece"
                    placeholder=""
                    class="w-full px-1 py-1 rounded-lg text-right focus:outline-none h-9"
                    :value="newTitle"
                    @input="handleChangePieceTitle"
                />
            </div> -->
            <base-input
                input-type="text"
                name="piece"
                @input="handleChangePieceTitle"
                :value="newTitle"
                :placeholder="t('pieces.modal.create.placeholder')"
                class="w-full mb-6 font-readex text-base font-light focus:outline-none"
            />
            <div v-if="errorMessage">
                <p class="text-red-500 text-sm">{{ errorMessage }}</p>
            </div>
            <div v-if="successMessage">
                <p class="text-green-400 text-sm">{{ successMessage }}</p>
            </div>
        </Form>
    </card-box-modal>

    <card-box-modal
        v-model="editModalActive"
        :title="t('pieces.modal.edit.title')"
        button="bg-main"
        has-cancel
        @confirm="confirmEdit"
    >
        <Form :validation-schema="schema"  id="EditForm">
            <div class="border border-gray-400 rounded-lg mx-10">
                <input
                    type="text"
                    placeholder=""
                    class="w-full px-1 py-1 rounded-lg text-right focus:outline-none h-9"
                    :value="selectedPiece?.title"
                    @input="handleChangePieceTitle"
                /></div>
            <!-- <base-input
                input-type="text"
                name="piece"
                @input="handleChangePieceTitle"
                :value="selectedPiece?.title"
                :placeholder="t('pieces.modal.create.placeholder')"
                class="w-full mb-6 font-readex text-base font-light focus:outline-none"
            /> -->
            <div v-if="errorMessage">
                <p class="text-red-500 text-sm">{{ errorMessage }}</p>
            </div>
            <div v-if="successMessage">
                <p class="text-green-400 text-sm">{{ successMessage }}</p>
            </div>
        </Form>
    </card-box-modal>
    <card-box-modal
        v-model="isModalDangerActive"
        :title="t('pieces.modal.delete.title')"
        button="bg-main"
        has-cancel
        @confirm="confirmDelete"
    >
        <p>Are you sure?</p>
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
                <td data-label="Created" class="whitespace-nowrap">
                    <small class="text-gray-500 dark:text-slate-400">{{
                        format(new Date(piece.createdAt), "dd-MM-yyyy HH:mm")
                    }}</small>
                </td>
                <td data-label="Title">
                    {{ piece.title }}
                </td>
                <td data-label="PieceId">
                    {{ piece.id }}
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
        :total-row="tableData.length"
        language="en"
        :page-size-menu="[5, 10, 20]"
        @change="change"
        class="my-8"
    />
</template>
