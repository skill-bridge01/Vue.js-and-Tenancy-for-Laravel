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

import { mdiPlus, mdiTrashCan, mdiPencil, mdiFileEyeOutline } from "@mdi/js";
import CardBoxModal from "@/components/CardBoxModal.vue";
import * as Yup from "yup";
import { Form } from "vee-validate";
import TableCheckboxCell from "@/components/TableCheckboxCell.vue";
import BaseIcon from "@/components/BaseIcon.vue";
import BaseButtons from "@/components/BaseButtons.vue";
import BaseInput from "@/components/BaseInput.vue";
import BaseCheckbox from "@/components/BaseCheckbox.vue";
import BaseButton from "@/components/BaseButton.vue";
import { format } from "date-fns";
import { Page } from "v-page";
// Importing the lodash library
import { mdiSortAscending, mdiSortDescending } from "@mdi/js";

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

const emits = defineEmits({});
const isNewModalActive = ref(false);
const showNewModal = () => {
    document.getElementById("NewForm").reset();
    isChecked.value = false;
    isShown.value = false;
    isNewModalActive.value = true;
};

defineExpose({
    showNewModal,
});

const { t } = useI18n();

const editModalActive = ref(false);

const servicesStore = useServicesStore();
const { services, selectedService, loading } = storeToRefs(useServicesStore());

const isModalDangerActive = ref(false);

const perPage = ref(5);

const currentPage = ref(1);

const checkedRows = ref([]);
// a value to check for sort
const sort = ref(false);
const ascending = ref(false);
const sortColumn = ref("");
const updatedList = ref([]);

// new piece
const newTitle = ref("");
const isChecked = ref(false);
const isCheckedNum = ref(0);
const isShown = ref(false);
const isShownNum = ref(0);
const errorMessage = ref(null);
const successMessage = ref(null);

onBeforeMount(() => {
    // invoicesStore.fetchLocalData()
    servicesStore.fetch();
});

onUnmounted(() => {
    servicesStore.clear();
});

const schema = Yup.object().shape({
    service: Yup.string().min(1).required(),
});

const FIELDS = ref([
    { label: t("services.table.actions"), id: "actions" },
    { label: t("services.table.date"), id: "createdAt" },
    { label: t("services.table.checked"), id: "checked" },
    { label: t("services.table.shown"), id: "shown" },
    { label: t("services.table.service"), id: "title" },
    { label: t("services.table.serviceNumber"), id: "id" },
]);

watchEffect(() => {
    FIELDS.value.forEach((item) => {
        switch (item.id) {
            case "actions":
                item.label = t("services.table.actions");
                break;
            case "createdAt":
                item.label = t("services.table.date");
                break;
            case "title":
                item.label = t("services.table.service");
                break;
            case "checked":
                item.label = t("services.table.checked");
                break;
            case "shown":
                item.label = t("services.table.shown");
                break;
            case "id":
                item.label = t("services.table.serviceNumber");
                break;
            default:
                break;
        }
    });
});

const tableData = computed(() => {
    const newData = services.value.map((service) => {
        let data = {
            createdAt: service["created_at"],
            title: service["services_title"],
            id: service["id"],
            isChecked: service["is_checked"],
            isShown: service["is_shown"],
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
const handleClickEdit = (service) => {
    servicesStore.setSelectedService(service);
    newTitle.value = service.title;
    isCheckedNum.value = service.isChecked;
    isShownNum.value = service.isShown;
    if (service.isChecked == 1) {
        isChecked.value = true;
    } else {
        isChecked.value = false;
    }
    if (service.isShown == 1) {
        isShown.value = true;
    } else {
        isShown.value = false;
    }
    editModalActive.value = true;
};

const handleClickDelete = (service) => {
    servicesStore.setSelectedService(service);
    isModalDangerActive.value = true;
};

const handleChangeServiceTitle = (event) => {
    newTitle.value = event.target.value;
};

const confirm = () => {
    if (isChecked.value) {
        isCheckedNum.value = 1;
    } else {
        isCheckedNum.value = 0;
    }
    if (isShown.value) {
        isShownNum.value = 1;
    } else {
        isShownNum.value = 0;
    }
    console.log("newTitile", newTitle.value);
    servicesStore
        .create(newTitle.value, isCheckedNum.value, isShownNum.value)
        .then((res) => {
            if (res.error) {
                console.log("ServiceCreateError", res.error);
                newTitle.value = "";
                isChecked.value = false;
                isShown.value = false;
                document.getElementById("NewForm").reset();
                errorMessage.value = t("services.modal.existError");
                setTimeout(() => {
                    errorMessage.value = null;
                }, 1500);
            } else if (res.success) {
                newTitle.value = "";
                isChecked.value = false;
                isShown.value = false;
                document.getElementById("NewForm").reset();
                successMessage.value = t("services.modal.create.success");
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
            newTitle.value = "";
            isChecked.value = false;
            isShown.value = false;
        });
};

const confirmEdit = () => {
    if (isChecked.value) {
        isCheckedNum.value = 1;
    } else {
        isCheckedNum.value = 0;
    }
    if (isShown.value) {
        isShownNum.value = 1;
    } else {
        isShownNum.value = 0;
    }
    servicesStore
        .edit(
            selectedService.value.id,
            newTitle.value,
            isCheckedNum.value,
            isShownNum.value
        )
        .then((res) => {
            console.log('res123', res);
            if (res.data.success) {
                successMessage.value = t("services.modal.edit.success");
                setTimeout(() => {
                    // document.getElementById("EditForm").reset();
                    successMessage.value = null;
                    editModalActive.value = false;
                    newTitle.value = "";
                    isChecked.value = false;
                    isShown.value = false;
                }, 1500);
            }
        })
        .catch((err) => {
            console.log(err);
            errorMessage.value = t("services.modal.existError");
            setTimeout(() => {
                errorMessage.value = null;
                newTitle.value = "";
            }, 1500);
        })
        .finally(() => {
            newTitle.value = "";
            isChecked.value = false;
            isShown.value = false;
        });
};

const confirmDelete = () => {
    servicesStore
        .delete(selectedService.value.id)
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
        :title="t('services.modal.create.title')"
        :button-label="t('common.save')"
        button="bg-main"
        @confirm="confirm"
        has-cancel
        :showModal="true"
    >
        <Form :validation-schema="schema" id="NewForm">
            <!-- <div class="border border-gray-400 rounded-lg mx-10">
                <input
                    type="text"
                    placeholder=""
                    class="w-full px-1 py-1 rounded-lg text-right focus:outline-none h-9"
                    :value="newTitle"
                    @input="handleChangeServiceTitle"
                />
            </div> -->
            <base-input
                input-type="text"
                name="service"
                @input="handleChangeServiceTitle"
                :value="newTitle"
                :placeholder="t('services.modal.create.placeholder')"
                class="w-full mt-5 mb-1 font-readex text-base font-light focus:outline-none"
            />
            <div v-if="errorMessage" class="text-right">
                <p class="text-red-500 text-sm">{{ errorMessage }}</p>
            </div>
            <div v-if="successMessage" class="text-right">
                <p class="text-green-500 text-sm">{{ successMessage }}</p>
            </div>
            <div class="flex justify-center pt-3 items-center gap-10">
                <div class="flex items-center gap-2">
                    <input type="checkbox" v-model="isChecked" />
                    <label>{{ t("common.check") }}</label>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" v-model="isShown" />
                    <label>{{ t("common.show") }}</label>
                </div>
            </div>
        </Form>
    </card-box-modal>

    <card-box-modal
        v-model="editModalActive"
        :title="t('services.modal.edit.title')"
        button="bg-main"
        :button-label="t('common.update')"
        has-cancel
        @confirm="confirmEdit"
        :showModal="true"
    >
        <div class="border border-gray-400 rounded-lg mx-5">
            <input
                type="text"
                placeholder=""
                class="w-full px-1 py-1 rounded-lg text-right focus:outline-none h-9"
                :value="selectedService?.title"
                @input="handleChangeServiceTitle"
            />
        </div>
        <div v-if="errorMessage" class="text-right mx-5">
            <p class="text-red-500 text-sm">{{ errorMessage }}</p>
        </div>
        <div v-if="successMessage" class="text-right mx-5">
            <p class="text-green-500 text-sm">{{ successMessage }}</p>
        </div>
        <div class="flex justify-center pt-3 items-center gap-10">
            <div class="flex items-center gap-2">
                <input type="checkbox" v-model="isChecked" />
                <label>{{ t("common.check") }}</label>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" v-model="isShown" />
                <label>{{ t("common.show") }}</label>
            </div>
        </div>
    </card-box-modal>
    <card-box-modal
        v-model="isModalDangerActive"
        :title="t('services.modal.delete.title')"
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
            <tr v-for="service in itemsPaginated" :key="service.id">
                <td class="before:hidden whitespace-nowrap">
                    <BaseButtons type="justify-start lg:justify-end" no-wrap>
                        <BaseButton
                            color="brandGreen"
                            :icon="mdiTrashCan"
                            small
                            border="border"
                            borderColor="brandBorder"
                            @click="handleClickDelete(service)"
                        />
                        <BaseButton
                            color="white"
                            :icon="mdiPencil"
                            small
                            border="border"
                            borderColor="brandBorder"
                            @click="() => handleClickEdit(service)"
                        />
                    </BaseButtons>
                </td>
                <td :data-label="t('services.table.date')" class="whitespace-nowrap">
                    <small class="text-gray-500 dark:text-slate-400">{{
                        format(new Date(service.createdAt), "dd-MM-yyyy HH:mm")
                    }}</small>
                </td>
                <td :data-label="t('services.table.checked')">
                    {{ service.isChecked ? "true" : "false" }}
                    <!-- {{ service.isChecked ? <input type="checkbox" checked/> : <input type="checkbox" /> }} -->
                    <!-- <input type="checkbox" {{ service.isChecked ? 'checked' : '' }} /> -->
                    <!-- <input type="checkbox" checked={service.isChecked} /> -->
                </td>
                <td :data-label="t('services.table.shown')">
                    {{ service.isShown ? "true" : "false" }}
                </td>
                <td :data-label="t('services.table.service')">
                    {{ service.title }}
                </td>

                <td :data-label="t('services.table.serviceNumber')">
                    {{ service.id }}
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
