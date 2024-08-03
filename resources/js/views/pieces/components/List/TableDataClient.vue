<script setup>
import {
    computed,
    ref,
    onBeforeMount,
    onMounted,
    onUnmounted,
    watchEffect,
} from "vue";
import BaseChooseButton from "@/components/BaseChooseButton.vue";
import { useI18n } from "vue-i18n";
import * as Yup from "yup";
import { Form } from "vee-validate";
import { mdiTrashCan, mdiPencil } from "@mdi/js";
import CardBoxModal from "@/components/CardBoxModal.vue";
import TableCheckboxCell from "@/components/TableCheckboxCell.vue";
import BaseIcon from "@/components/BaseIcon.vue";
import BaseInput from "@/components/BaseInput.vue";
import BaseButtons from "@/components/BaseButtons.vue";
import BaseButton from "@/components/BaseButton.vue";
import { format } from "date-fns";
import { Page } from "v-page";
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
    document.getElementById("profile-picture").value = "";

    previewImage.value = "";
    pieceImage.value = "";
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
const sort = ref(false);
const ascending = ref(false);
const sortColumn = ref("");
const errorMessage = ref(null);
const successMessage = ref(null);
const newTitle = ref("");
const previewImage = ref("");
const pieceImage = ref("");
const editPieceImage = ref("");
const subDomain = ref("");
const previewEditImage = ref("");

onBeforeMount(() => {
    piecesStore.fetch();
    subDomain.value = window.location.hostname.split(".")[0];
});

onUnmounted(() => {
    piecesStore.clear();
});

const FIELDS = ref([
    { label: t("pieces.table.actions"), id: "actions" },
    { label: t("pieces.table.date"), id: "createdAt" },
    { label: t("pieces.table.image"), id: "image" },
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
            case "image":
                item.label = t("pieces.table.image");
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
            image: piece["image"],
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

const change = (pageState) => {
    console.log(pageState);
    currentPage.value = pageState.pageNumber;
    perPage.value = pageState.pageSize;
};

const handleFileChange = (event) => {
    pieceImage.value = event.target.files[0];
    const reader = new FileReader();
    reader.readAsDataURL(pieceImage.value);
    reader.onload = (e) => {
        previewImage.value = e.target.result;
    };
};

const handleFileEditChange = (event) => {
    editPieceImage.value = event.target.files[0];
    const reader = new FileReader();
    reader.readAsDataURL(editPieceImage.value);
    reader.onload = (e) => {
        previewEditImage.value = e.target.result;
    };
};

// ** Actions
const handleClickEdit = (piece) => {
    document.getElementById("EditForm").reset();
    previewEditImage.value = "";
    piecesStore.setSelectedPiece(piece);
    newTitle.value = piece.title;
    editModalActive.value = true;
    console.log("selected piece", piece);
};

const handleClickDelete = (piece) => {
    piecesStore.setSelectedPiece(piece);
    isModalDangerActive.value = true;
};

const handleChangePieceTitle = (event) => {
    newTitle.value = event.target.value;
};

const confirm = () => {
    const formData = new FormData();
    formData.append("image", pieceImage.value);
    formData.append("title", newTitle.value);
    console.log("FormData", formData);
    piecesStore
        .create(formData)
        .then((res) => {
            if (res.error) {
                errorMessage.value = t("pieces.modal.existError");
                setTimeout(() => {
                    document.getElementById("NewForm").reset();
                    newTitle.value = "";
                    errorMessage.value = null;
                }, 1500);
            } else if (res.success) {
                successMessage.value = t("pieces.modal.create.success");
                setTimeout(() => {
                    document.getElementById("NewForm").reset();
                    newTitle.value = "";
                    successMessage.value = null;
                    isNewModalActive.value = false;
                }, 1500);
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
    errorMessage.value = null;
    successMessage.value = null;
    const formData = new FormData();
    formData.append("image", editPieceImage.value);
    formData.append("title", newTitle.value);
    console.log("FormData", formData);

    piecesStore
        .edit(selectedPiece.value.id, formData)
        .then((res) => {
            console.log("res", res.data);
            if (res.data.success) {
                successMessage.value = t("pieces.modal.edit.success");
                setTimeout(() => {
                    document.getElementById("EditForm").reset();
                    successMessage.value = null;
                    newTitle.value = "";
                    editModalActive.value = false;
                }, 1500);
            }
        })
        .catch((err) => {
            console.log("efef", err);
            errorMessage.value = t("pieces.modal.existError");
            setTimeout(() => {
                errorMessage.value = null;
                newTitle.value = "";
            }, 1500);
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
            <div class="mb-3 pt-8 text-center flex justify-center">
                <base-choose-button for="profile-picture" />
                <input
                    type="file"
                    name="logo"
                    id="profile-picture"
                    ref="profilePicture"
                    class="hidden"
                    @change="handleFileChange"
                    accept="image/*"
                />
            </div>
            <div class="mb-3 pt-0 text-center mx-auto">
                <img
                    v-if="previewImage"
                    :src="previewImage"
                    class="w-1/4 h-2/4 mx-auto"
                    alt="Profile Picture"
                />
            </div>
            <base-input
                input-type="text"
                name="piece"
                @input="handleChangePieceTitle"
                :value="newTitle"
                :placeholder="t('pieces.modal.create.placeholder')"
                class="w-full mb-1 font-readex text-base font-light focus:outline-none"
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
        :title="t('pieces.modal.edit.title')"
        :button-label="t('common.update')"
        button="bg-main"
        has-cancel
        @confirm="confirmEdit"
        :showModal="true"
    >
        <Form :validation-schema="schema" id="EditForm">
            <div class="mb-3 pt-5 text-center flex justify-center">
                <base-choose-button for="profile-picture-edit" />
                <!-- <input id="file-upload" type="file" style="display: none" /> -->
                <input
                    type="file"
                    name="logo"
                    id="profile-picture-edit"
                    ref="profilePictureEdit"
                    class="hidden"
                    @change="handleFileEditChange"
                    accept="image/*"
                />
            </div>
            <div class="mb-3 pt-0 text-center mx-auto">
                <img
                    v-if="previewEditImage"
                    :src="previewEditImage"
                    class="w-1/4 h-2/4 mx-auto"
                    alt="Profile Picture"
                />
                <div v-else>
                    <div v-if="selectedPiece">
                        <img
                            v-if="selectedPiece.image!=null"
                            :src="
                                `/storage/tenant` +
                                subDomain +
                                `/app/public/` +
                                selectedPiece.image
                            "
                            class="w-1/4 h-2/4 mx-auto"
                            alt="Profile Picture"
                        />
                        <img
                            v-else
                            src="/images/home/default-piece.png"
                            class="w-1/4 h-2/4 mx-auto"
                            alt="Profile Picture"
                        />
                        
                    </div>
                </div>
            </div>
            <div class="border border-gray-400 rounded-lg mx-5">
                <input
                    type="text"
                    placeholder=""
                    class="w-full px-1 py-1 rounded-lg text-right focus:outline-none h-9"
                    :value="selectedPiece?.title"
                    @input="handleChangePieceTitle"
                />
            </div>
            <div v-if="errorMessage" class="text-right mx-5">
                <p class="text-red-500 text-sm">{{ errorMessage }}</p>
            </div>
            <div v-if="successMessage" class="text-right mx-5">
                <p class="text-green-500 text-sm">{{ successMessage }}</p>
            </div>
        </Form>
    </card-box-modal>
    <card-box-modal
        v-model="isModalDangerActive"
        :title="t('pieces.modal.delete.title')"
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
                <td
                    :data-label="t('pieces.table.date')"
                    class="whitespace-nowrap"
                >
                    <small class="text-gray-500 dark:text-slate-400">{{
                        format(new Date(piece.createdAt), "dd-MM-yyyy HH:mm")
                    }}</small>
                </td>
                <td :data-label="t('pieces.table.image')">
                    <div class="flex justify-end">
                        <img
                            v-if="piece.image"
                            :src="
                                `/storage/tenant` +
                                subDomain +
                                `/app/public/` +
                                piece.image
                            "
                            class="h-[50px]"
                            alt="piece image"
                        />
                        <img
                            v-else
                            src="/images/home/default-piece.png"
                            class="h-[50px]"
                            alt="piece image1"
                        />
                    </div>
                </td>
                <td :data-label="t('pieces.table.piece')">
                    {{ piece.title }}
                </td>
                <td :data-label="t('pieces.table.pieceNumber')">
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
