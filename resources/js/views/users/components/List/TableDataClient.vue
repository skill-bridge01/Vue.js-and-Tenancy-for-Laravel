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
import { Form, Field } from "vee-validate";
import { mdiPlus, mdiTrashCan, mdiPencil } from "@mdi/js";
import CardBoxModal from "@/components/CardBoxModal.vue";
import TableCheckboxCell from "@/components/TableCheckboxCell.vue";
import BaseIcon from "@/components/BaseIcon.vue";
import BaseInput from "@/components/BaseInput.vue";
import BaseButtons from "@/components/BaseButtons.vue";
import BaseButton from "@/components/BaseButton.vue";
import { HollowDotsSpinner } from "epic-spinners";
import { format } from "date-fns";
import { Page } from "v-page";
// Importing the lodash library
import { mdiSortAscending, mdiSortDescending } from "@mdi/js";

import { useUsersStore } from "@/store/pinia/users";
import { storeToRefs } from "pinia";
import { AtomSpinner } from "epic-spinners";
import {
    NotificationProvider,
    dispatchNotification,
} from "@/components/Notification";
import { userAction } from "@/services/user.service";

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
const usersStore = useUsersStore();
const { services, selectedService, loading } = storeToRefs(useUsersStore());
const isModalDangerActive = ref(false);
const perPage = ref(5);
const currentPage = ref(1);
const checkedRows = ref([]);
// a value to check for sort
const sort = ref(false);
const ascending = ref(false);
const sortColumn = ref("");
// new piece
const newEmail = ref("");
const updatedUsername = ref("");
const selectedUser = ref("");
const oldPassword = ref("");
const newUsername = ref("");
const newPassword = ref("");
const newRepassword = ref("");
const showAnimator = ref(false);
const errorMessage = ref(null);
const successMessage = ref(null);

onBeforeMount(() => {
    // invoicesStore.fetchLocalData()
    usersStore.fetch();
});

onUnmounted(() => {
    usersStore.clear();
});

const FIELDS = ref([
    { label: t("users.table.actions"), id: "actions" },
    { label: t("users.table.date"), id: "createdAt" },
    { label: t("users.table.email"), id: "email" },
    { label: t("users.table.username"), id: "username" },
    { label: t("users.table.Number"), id: "id" },
]);

watchEffect(() => {
    FIELDS.value.forEach((item) => {
        switch (item.id) {
            case "actions":
                item.label = t("users.table.actions");
                break;
            case "createdAt":
                item.label = t("users.table.date");
                break;
            case "email":
                item.label = t("users.table.email");
                break;
            case "username":
                item.label = t("users.table.username");
                break;
            case "id":
                item.label = t("users.table.Number");
                break;
            default:
                break;
        }
    });
});

// const schema = Yup.object().shape({
//     email: Yup.string()

//         .matches(/^[A-Z0-9._%+-]+@gmail\.com$/i, "Email must be @gmail.com")
//         .required(),
//     username: Yup.string().min(6).required(),
//     password: Yup.string().min(8).required(),
//     password_confirmation: Yup.string()
//         .required()
//         .oneOf([Yup.ref("password")], "Passwords do not match"),
// });

const schema = Yup.object().shape({
    email: Yup.string().email(),
    username: Yup.string().min(6).required(),
    password: Yup.string().min(8).required(),
    password_confirmation: Yup.string()
        .required()
        .oneOf([Yup.ref("password")], "Passwords do not match"),
});

const schema1 = Yup.object().shape({
    username: Yup.string().min(6).required(),
    old_password: Yup.string().min(8),
    password: Yup.string().min(8),
    password_confirmation: Yup.string().oneOf(
        [Yup.ref("password")],
        "Passwords do not match"
    ),
});

const tableData = computed(() => {
    const newData = services.value.map((service) => {
        let data = {
            createdAt: service["created_at"],
            username: service["username"],
            email: service["email"],
            id: service["id"],
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
    document.getElementById("EditForm").reset();
    console.log(
        "kk",
        service.email,
        service,
        "service.name",
        service.name,
        "selectedService.value.username",
        service.username
    );
    usersStore.setSelectedService(service);
    oldPassword.value = "";
    newPassword.value = "";
    newRepassword.value = "";
    // selectedUser.value=service.username
    updatedUsername.value = service.username;

    editModalActive.value = true;
};

const handleClickDelete = (service) => {
    usersStore.setSelectedService(service);
    isModalDangerActive.value = true;
};

const handleChangeEmail = (event) => {
    newEmail.value = event.target.value;
};
const handleUpdateUsername = (event) => {
    updatedUsername.value = event.target.value;
};
const handleChangeUsername = (event) => {
    newUsername.value = event.target.value;
};
const handleChangeOldPassword = (event) => {
    console.log("oldPwd");
    oldPassword.value = event.target.value;
};
const handleChangePassword = (event) => {
    newPassword.value = event.target.value;
};
const handleChangeRePassword = (event) => {
    newRepassword.value = event.target.value;
};

//Email address check Function

const isValidGmailAddress = (address) => {
    return /^[a-zA-Z0-9._%+-]+@gmail\.com$/.test(address);
};

// const confirm = () => {
//     errorMessage.value = null;
//     console.log("verifyEmail", isValidGmailAddress("example@gmail.com"));
//     // showAnimator.value = true;
//     console.log("newTitile", newEmail.value, newUsername.value);
//     if (newPassword.value.length > 7 && newRepassword.value.length > 7) {
//         if (newPassword.value == newRepassword.value) {
//             if (newEmail.value != "" || newEmail.value == "") {
//                 showAnimator.value = true;
//                 usersStore
//                     .create(
//                         newEmail.value,
//                         newUsername.value,
//                         newPassword.value
//                     )
//                     .then((res) => {
//                         if (res.error) {
//                             console.log("errorExcution", res.error, res);

//                             showAnimator.value = false;
//                             errorMessage.value = res.error;
//                             document.getElementById("NewForm").reset();
//                             setTimeout(() => {
//                                 errorMessage.value = null;
//                                 newEmail.value = "";
//                                 newUsername.value = "";
//                                 newPassword.value = "";
//                             }, 1000);
//                         } else if (res.success) {
//                             newEmail.value = "";
//                             newUsername.value = "";
//                             newPassword.value = "";
//                             document.getElementById("NewForm").reset();
//                             showAnimator.value = false;
//                             successMessage.value = res.message;
//                             setTimeout(() => {
//                                 successMessage.value = null;
//                                 isNewModalActive.value = false;
//                             }, 1000);
//                         }
//                     })
//                     .catch((err) => {
//                         newEmail.value = "";
//                         newUsername.value = "";
//                         newPassword.value = "";
//                         showAnimator.value = false;
//                     })
//                     .finally(() => {
//                         newEmail.value = "";
//                         newUsername.value = "";
//                         newPassword.value = "";
//                         showAnimator.value = false;
//                     });
//             } else {
//                 errorMessage.value = "Please input correct Email";
//                 setTimeout(() => {
//                     errorMessage.value = null;
//                 }, 1000);
//             }
//         } else {
//             errorMessage.value = "Passwords do not match";
//             setTimeout(() => {
//                 errorMessage.value = null;
//             }, 1000);
//         }
//     } else {
//         console.log("Invalid");
//         errorMessage.value = "Please input correct value";
//         setTimeout(() => {
//             errorMessage.value = null;
//         }, 1000);
//     }
// };

const confirm = () => {
    errorMessage.value = null;
    console.log("verifyEmail", isValidGmailAddress("example@gmail.com"));
    // showAnimator.value = true;
    console.log("newTitile", newEmail.value, newUsername.value);

    showAnimator.value = true;
    usersStore
        .create(newEmail.value, newUsername.value, newPassword.value)
        .then((res) => {
            if (res.error) {
                console.log("errorExcution", res.error, res);

                showAnimator.value = false;
                errorMessage.value = res.error;
                document.getElementById("NewForm").reset();
                setTimeout(() => {
                    errorMessage.value = null;
                    newEmail.value = "";
                    newUsername.value = "";
                    newPassword.value = "";
                }, 1000);
            } else if (res.success) {
                newEmail.value = "";
                newUsername.value = "";
                newPassword.value = "";
                document.getElementById("NewForm").reset();
                showAnimator.value = false;
                successMessage.value = res.message;
                setTimeout(() => {
                    successMessage.value = null;
                    isNewModalActive.value = false;
                }, 1000);
            }
        })
        .catch((err) => {
            newEmail.value = "";
            newUsername.value = "";
            newPassword.value = "";
            showAnimator.value = false;
        })
        .finally(() => {
            newEmail.value = "";
            newUsername.value = "";
            newPassword.value = "";
            showAnimator.value = false;
        });
};

const confirmEdit = () => {
    console.log("ID");
    errorMessage.value = null;
    console.log(
        "ID",
        selectedService.value.username,
        selectedService.value.id,
        updatedUsername.value,
        "Passwords",
        oldPassword.value,
        newPassword.value,
        newRepassword.value
    );
    if (oldPassword.value != "") {
        usersStore
            .edit(
                selectedService.value.id,
                updatedUsername.value,
                oldPassword.value,
                newPassword.value
            )
            .then((res) => {
                console.log("res", res);
                if (res.error) {
                    console.log("errorExcution", res.error, res);
                    errorMessage.value = res.error;
                    setTimeout(() => {
                        errorMessage.value = null;
                    }, 1000);
                } else if (res.success) {
                    oldPassword.value = "";
                    updatedUsername.value = "";
                    newPassword.value = "";
                    newRepassword.value = "";

                    successMessage.value = res.message;
                    setTimeout(() => {
                        successMessage.value = null;
                        editModalActive.value = false;
                    }, 1000);
                }
            })
            .catch((err) => {
                console.log("err", err);
            })
            .finally(() => {
                oldPassword.value = "";
                // updatedUsername.value = "";
                newPassword.value = "";
                newRepassword.value = "";
            });
    } else {
        usersStore
            .edit1(selectedService.value.id, updatedUsername.value)
            .then((res) => {
                console.log("res", res);
                if (res.error) {
                    console.log("errorExcution", res.error, res);
                    errorMessage.value = res.error;
                    setTimeout(() => {
                        errorMessage.value = null;
                    }, 1000);
                } else if (res.success) {
                    updatedUsername.value = "";
                    successMessage.value = res.message;
                    setTimeout(() => {
                        successMessage.value = null;
                        editModalActive.value = false;
                    }, 1000);
                }
            })
            .catch((err) => {
                console.log("err", err);
            })
            .finally(() => {
                updatedUsername.value = "";
            });
    }
};

// const confirmEdit = () => {
//     errorMessage.value = null;
//     console.log(
//         "ID",
//         selectedService.value.username,
//         selectedService.value.id,
//         updatedUsername.value,
//         "Passwords",
//         oldPassword.value,
//         newPassword.value,
//         newRepassword.value
//     );
//     if (oldPassword.value != "") {
//         if (newPassword.value.length > 7 && newRepassword.value.length > 7) {
//             if (newPassword.value == newRepassword.value) {
//                 usersStore
//                     .edit(
//                         selectedService.value.id,
//                         updatedUsername.value,
//                         oldPassword.value,
//                         newPassword.value
//                     )
//                     .then((res) => {
//                         console.log("res", res);
//                         if (res.error) {
//                             console.log("errorExcution", res.error, res);
//                             errorMessage.value = res.error;
//                             setTimeout(() => {
//                                 errorMessage.value = null;
//                             }, 1000);
//                         } else if (res.success) {
//                             oldPassword.value = "";
//                             updatedUsername.value = "";
//                             newPassword.value = "";
//                             newRepassword.value = "";

//                             successMessage.value = res.message;
//                             setTimeout(() => {
//                                 successMessage.value = null;
//                                 editModalActive.value = false;
//                             }, 1000);
//                         }
//                     })
//                     .catch((err) => {
//                         console.log("err", err);
//                     })
//                     .finally(() => {
//                         oldPassword.value = "";
//                         // updatedUsername.value = "";
//                         newPassword.value = "";
//                         newRepassword.value = "";
//                     });
//             } else {
//                 errorMessage.value = "Passwords do not match";
//                 setTimeout(() => {
//                     errorMessage.value = null;
//                 }, 1000);
//             }
//         } else {
//             errorMessage.value = "Password must be at least 8 characters";
//             setTimeout(() => {
//                 errorMessage.value = null;
//             }, 1000);
//         }
//     } else {
//         usersStore
//             .edit1(selectedService.value.id, updatedUsername.value)
//             .then((res) => {
//                 console.log("res", res);
//                 if (res.error) {
//                     console.log("errorExcution", res.error, res);
//                     errorMessage.value = res.error;
//                     setTimeout(() => {
//                         errorMessage.value = null;
//                     }, 1000);
//                 } else if (res.success) {
//                     updatedUsername.value = "";
//                     successMessage.value = res.message;
//                     setTimeout(() => {
//                         successMessage.value = null;
//                         editModalActive.value = false;
//                     }, 1000);
//                 }
//             })
//             .catch((err) => {
//                 console.log("err", err);
//             })
//             .finally(() => {
//                 updatedUsername.value = "";
//             });
//     }
// };

const confirmDelete = () => {
    usersStore
        .delete(selectedService.value.id)
        .then((res) => {
            console.log(res);
        })
        .catch((err) => {
            console.log(err);
        })
        .finally(() => {});
};

// submit
const onSubmit = (values) => {
    console.log("values => ");
    console.log(values);
};
</script>

<template>
    <card-box-modal
        v-model="isNewModalActive"
        title="Creat a new user"
        :button-label="t('common.save')"
        button="bg-main"
        has-cancel
        :has-buttons="false"
        @confirm=""
        :showModal="true"
    >
        <Form id="NewForm" :validation-schema="schema" @submit="confirm">
            <base-input
                input-type="email"
                name="email"
                @input="handleChangeEmail"
                :value="newEmail"
                :icon-src="`/images/login/email_grey.svg`"
                :placeholder="t('auth.login.email')"
                class="w-full mb-6 font-readex text-base font-light"
            />
            <base-input
                input-type="text"
                name="username"
                @input="handleChangeUsername"
                :value="newUsername"
                :icon-src="`/images/home/placeholder_user.svg`"
                :placeholder="t('auth.login.username')"
                class="w-full mb-6 font-readex text-base font-light"
            />
            <base-input
                input-type="password"
                name="password"
                @input="handleChangePassword"
                :value="newPassword"
                :icon-src="`/images/login/password_grey.svg`"
                :placeholder="t('auth.login.password')"
                class="w-full font-readex text-base font-light mb-6"
            />
            <base-input
                input-type="password"
                name="password_confirmation"
                @input="handleChangeRePassword"
                :value="newRepassword"
                :icon-src="`/images/login/password_grey.svg`"
                :placeholder="t('auth.signup.confirmPassword')"
                class="w-full font-readex text-base font-light"
            />
            <div v-if="errorMessage">
                <p class="text-red-500 text-sm">{{ errorMessage }}</p>
            </div>
            <div v-if="successMessage">
                <p class="text-green-400 text-sm">{{ successMessage }}</p>
            </div>
            <div class="mt-4">
                <BaseButtons type="justify-start">
                    <BaseButton
                        :label="t('common.save')"
                        color="bg-main"
                        type="submit"
                    />
                    <BaseButton
                        :label="t('modal.logout.back')"
                        color="bg-main"
                        @click="isNewModalActive = !isNewModalActive"
                    />
                </BaseButtons>
            </div>
        </Form>
    </card-box-modal>

    <card-box-modal
        v-model="editModalActive"
        :title="t('users.modal.edit.title')"
        button="bg-main"
        :button-label="t('common.update')"
        has-cancel
        :has-buttons="false"
        @confirm=""
        :showModal="true"
    >
        <Form :validation-schema="schema1" id="EditForm" @submit="confirmEdit">
            <div
                class="flex items-center h-10 bg-white rounded-lg border pr-4 mb-5 mt-10"
            >
                <div class="relative text-gray-600 search-bar mx-3 flex w-full">
                    <Field
                        name="username"
                        class="text-sm border-none focus:outline-none text-right w-full pr-0"
                        :placeholder="t('account.username')"
                        v-model="updatedUsername"
                        @input="handleUpdateUsername"
                    />
                </div>
                <div>
                    <img :src="`/images/home/placeholder_user.svg`" />
                </div>
            </div>

            <!-- <base-input
                input-type="text"
                name="username"
                :icon-src="`/images/home/placeholder_user.svg`"
                :placeholder="t('account.username')"
                value="updatedUsername"
                @input="handleUpdateUsername"
                class="w-full mb-6 font-readex text-base font-light"
            /> -->

            <!-- <div
                class="flex items-center h-10 bg-white rounded-lg border pr-4 mb-5 mt-3"
            >
                <div class="relative text-gray-600 search-bar mx-3 flex w-full">
                    <input
                        class="text-sm border-none focus:outline-none text-right w-full pr-0"
                        type="password"
                        name="password"
                        :placeholder="t('account.oldPassword')"
                        v-model="oldPassword"
                        @blur="handleBlur"
                    />
                </div>
                <div>
                    <img :src="`/images/login/password_grey.svg`" />
                </div>
            </div> -->

            <base-input
                input-type="password"
                name="old_password"
                @input="handleChangeOldPassword"
                :value="oldPassword"
                :icon-src="`/images/login/password_grey.svg`"
                :placeholder="t('account.oldPassword')"
                class="w-full font-readex text-base font-light mb-6"
            />

            <!-- <div
                class="flex items-center h-10 bg-white rounded-lg border pr-4 mb-5 mt-3"
            >
                <div class="relative text-gray-600 search-bar mx-3 flex w-full">
                    <input
                        class="text-sm border-none focus:outline-none text-right w-full pr-0"
                        type="password"
                        name="password"
                        :placeholder="t('account.newPassword')"
                        v-model="newPassword"
                        @blur="handleBlur"
                    />
                </div>
                <div>
                    <img :src="`/images/login/password_grey.svg`" />
                </div>
            </div> -->
            <base-input
                input-type="password"
                name="password"
                @input="handleChangePassword"
                :value="newPassword"
                :icon-src="`/images/login/password_grey.svg`"
                :placeholder="t('account.newPassword')"
                class="w-full font-readex text-base font-light mb-6"
            />
            <!-- <div
                class="flex items-center h-10 bg-white rounded-lg border pr-4 mb-5 mt-3"
            >
                <div class="relative text-gray-600 search-bar mx-3 flex w-full">
                    <input
                        class="text-sm border-none focus:outline-none text-right w-full pr-0"
                        type="password"
                        name="password_confirmation"
                        :placeholder="t('account.confirmPassword')"
                        v-model="newRepassword"
                        @blur="handleBlur"
                    />
                </div>
                <div>
                    <img :src="`/images/login/password_grey.svg`" />
                </div>
            </div> -->
            <base-input
                input-type="password"
                name="password_confirmation"
                @input="handleChangeRePassword"
                :value="newRepassword"
                :icon-src="`/images/login/password_grey.svg`"
                :placeholder="t('account.confirmPassword')"
                class="w-full font-readex text-base font-light"
            />
            <div v-if="errorMessage">
                <p class="text-red-500 text-sm">{{ errorMessage }}</p>
            </div>
            <div v-if="successMessage">
                <p class="text-green-400 text-sm">{{ successMessage }}</p>
            </div>
            <div class="mt-4">
                <BaseButtons type="justify-start">
                    <BaseButton
                        :label="t('common.update')"
                        color="bg-main"
                        type="submit"
                    />
                    <BaseButton
                        :label="t('modal.logout.back')"
                        color="bg-main"
                        @click="editModalActive = !editModalActive"
                    />
                </BaseButtons>
            </div>
        </Form>
    </card-box-modal>
    <card-box-modal
        v-model="isModalDangerActive"
        :title="t('users.modal.delete.title')"
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

    <table v-if="!showAnimator">
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
            <tr v-for="(user, index) in itemsPaginated" :key="user.id">
                <td class="before:hidden whitespace-nowrap">
                    <BaseButtons type="justify-start lg:justify-end" no-wrap>
                        <BaseButton
                            color="brandGreen"
                            :icon="mdiTrashCan"
                            small
                            border="border"
                            borderColor="brandBorder"
                            @click="handleClickDelete(user)"
                        />
                        <BaseButton
                            color="white"
                            :icon="mdiPencil"
                            small
                            border="border"
                            borderColor="brandBorder"
                            @click="() => handleClickEdit(user)"
                        />
                    </BaseButtons>
                </td>
                <td data-label="Created" class="whitespace-nowrap">
                    <small class="text-gray-500 dark:text-slate-400">{{
                        format(new Date(user.createdAt), "dd-MM-yyyy HH:mm")
                    }}</small>
                </td>
                <td data-label="Email">
                    {{ user.email }}
                </td>
                <td data-label="Name">
                    {{ user.username }}
                </td>

                <td data-label="PieceId">
                    {{ index + 1 }}
                </td>

                <TableCheckboxCell
                    v-if="checkable"
                    @checked="checked($event, client)"
                />
            </tr>
        </tbody>
    </table>

    <div class="flex justify-center" v-else>
        <hollow-dots-spinner
            :animation-duration="1000"
            :dot-size="15"
            :dots-num="3"
            color="#4BC49A"
        />
    </div>
    <!-- <NewPaginationBar :per-page="perPage" :total="invoices.length" v-model="currentPage" /> -->
    <NotificationProvider>
        <Page
            v-model="currentPage"
            :total-row="tableData.length"
            language="en"
            :page-size-menu="[5, 10, 20]"
            @change="change"
            class="my-8"
        />
    </NotificationProvider>
</template>
