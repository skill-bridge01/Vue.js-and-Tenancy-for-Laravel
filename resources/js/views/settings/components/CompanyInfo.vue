<script setup>
import { Form } from "vee-validate";
import CardBoxModal from "@/components/CardBoxModal.vue";
import BaseChooseButton from "@/components/BaseChooseButton.vue";
import BaseInput from "../../../components/BaseInput.vue";
import BaseIcon from "../../../components/BaseIcon.vue";
import { useAuthStore } from "@/store/pinia/auth";
import { useI18n } from "vue-i18n";
import * as Yup from "yup";
import { ref, computed, onBeforeMount } from "vue";
import axios from "axios";
import { storeToRefs } from "pinia";
import { mdiPencil, mdiCheck } from "@mdi/js";
const showSuccessMessage = ref(false);
const showErrorMessage = ref(false);
const showErrorName = ref(false);
const showErrorAddress = ref(false);
const showErrorEditName = ref(false);
const showErrorEditAddress = ref(false);
const showErrorImage = ref(false);
const previewImage = ref("");
const previewEditImage = ref("");
const authStore = useAuthStore();
const { company, selectedCompany } = storeToRefs(useAuthStore());
const editModalActive = ref(false);
const subDomain = ref("");
const form = ref({
    name: "",
    address: "",
    profile_picture: "",
    commercialNumber:"",
    taxNumber:""
});

const editForm = ref({
    name: "",
    address: "",
    profile_picture: "",
    commercialNumber:"",
    taxNumber:""
});

onBeforeMount(() => {
    authStore.fetchCompany();
    subDomain.value = window.location.hostname.split(".")[0];
});
const schema = computed(() => {
    const { t } = useI18n();
    return Yup.object().shape({
        name: Yup.string().required(t("validation.companyName.required")),
        address: Yup.string().required(t("validation.companyAddress.required")),
    });
});

const schemaEdit = Yup.object().shape({
    editName: Yup.string().min(1).required(),
    editAddress: Yup.string().min(1).required(),
});

const errorMessage = ref(null);
const successMessage = ref(null);

const { t } = useI18n();

const resetForm = () => {
    form.value = {
        name: "",
        address: "",
        profile_picture: "",
        commercialNumber:"",
        taxNumber:""
    };
    previewImage.value = "";
    // Clear the file input element
    const fileInput = document.getElementById("profile-picture");
    if (fileInput) {
        fileInput.value = null;
    }
};
const handleFileChange = (event) => {
    form.value.profile_picture = event.target.files[0];
    const reader = new FileReader();
    reader.readAsDataURL(form.value.profile_picture);
    reader.onload = (e) => {
        previewImage.value = e.target.result;
    };
};

const handleFileEditChange = (event) => {
    editForm.value.profile_picture = event.target.files[0];
    const reader = new FileReader();
    reader.readAsDataURL(editForm.value.profile_picture);
    reader.onload = (e) => {
        previewEditImage.value = e.target.result;
    };
};

const validateInfo = () => {
    showErrorName.value = form.value.name.trim() === "";
    showErrorAddress.value = form.value.address.trim() === "";
    showErrorImage.value = previewImage.value === "";
    setTimeout(() => {
        showErrorName.value = null;
        showErrorAddress.value = null;
        showErrorImage.value = null;
    }, 1500);
};

const validateEditInfo = () => {
    showErrorEditName.value = editForm.value.name.trim() === "";
    showErrorEditAddress.value = editForm.value.address.trim() === "";
    setTimeout(() => {
        showErrorName.value = null;
        showErrorAddress.value = null;
    }, 1500);
};

const handleClickEdit = (company) => {
    editForm.value.profile_picture = "";
    document.getElementById("EditForm").reset();
    authStore.setSelectedCompany(company);
    console.log("COMPANY", company[0]);
    editForm.value.name = company[0].name;
    editForm.value.address = company[0].address;
    editForm.value.profile_picture = company[0].logo;
    editForm.value.commercialNumber = company[0].commercial_number;
    editForm.value.taxNumber = company[0].tax_number;
    editModalActive.value = true;
};

const handleChangeCompanyName = (event) => {
    editForm.value.name = event.target.value;
};
const handleChangeCompanyAddress = (event) => {
    editForm.value.address = event.target.value;
};

const handleChangeCommercialNumber = (event) => {
    editForm.value.commercialNumber = event.target.value;
};
const handleChangeTaxNumber = (event) => {
    editForm.value.taxNumber = event.target.value;
};

const onSubmit = async (values, actions) => {
    validateInfo();
    console.log("Form data111:", showErrorName.value, showErrorAddress.value);
    if (!showErrorName.value && !showErrorAddress.value) {
        // Implement what happens when form data is valid
        console.log("Form data:", "2");
        const formData = new FormData();
        for (const key in form.value) {
            console.log(key, form.value[key]);
            formData.append(key, form.value[key]);
        }
        successMessage.value = null;
        const response = await axios.post("/api/company", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
        if (response.status) {
            console.log("here");
            successMessage.value = t("account.company.api.create");
            setTimeout(() => {
                successMessage.value = null;
                authStore.fetchCompany();
            }, 1500);
            // resetForm();
        }
    }
};

const confirmEdit = async () => {
    validateEditInfo();
    if (!showErrorName.value && !showErrorAddress.value) {
        // Implement what happens when form data is valid
        console.log("EditForm data:", "2");
        const formData = new FormData();
        for (const key in editForm.value) {
            console.log(key, editForm.value[key]);
            formData.append(key, editForm.value[key]);
        }
        successMessage.value = null;
        const response = await axios.post("/api/company-edit", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
        if (response.status) {
            console.log("here Edit");
            successMessage.value = t("account.company.api.create");
            setTimeout(() => {
                successMessage.value = null;
                authStore.fetchCompany();
            }, 1500);
        }
    }
};

const validateAndRegister = async () => {
    console.log("formData");

    try {
        const formData = new FormData();
        for (const key in form.value) {
            formData.append(key, form.value[key]);
        }
        console.log("formData", formData);
        const response = await axios.post("/api/company-info", formData);
        if (response.status) {
            console.log("here");
            showSuccessMessage.value = true;
            showErrorMessage.value = false;
            resetForm();
        } else {
            console.error("Unexpected response status:", response.status);
        }
    } catch (error) {
        if (error.isAxiosError && error.response) {
            console.error("Server validation errors:", error.response.data);
            errorMessage.value = error.response.data.message;
        } else {
            console.error("An error occurred:", error);
            errorMessage.value =
                "An error occurred while processing your request.";
        }
        showSuccessMessage.value = false;
        showErrorMessage.value = true;
    }
};
</script>
<template>
    <card-box-modal
        v-model="editModalActive"
        :title="t('account.company.edit')"
        :button-label="t('common.update')"
        button="bg-main"
        has-cancel
        @confirm="confirmEdit"
    >
        <Form :validation-schema="schemaEdit" id="EditForm">
            <label
                class="block text-blueGray-600 text-sm font-bold mb-2 pt-8 text-right"
                >{{ t("account.company.companyLogo") }}</label
            >
            <div class="mb-3 text-center flex justify-center">
                <base-choose-button for="profile-picture-edit" />
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
                    <img
                        v-if="selectedCompany"
                        :src="
                            `/storage/tenant` +
                            subDomain +
                            `/app/public/` +
                            selectedCompany[0]?.logo
                        "
                        class="w-1/4 h-2/4 mx-auto"
                        alt="Profile Picture"
                    />
                </div>
            </div>

            <label
                class="text-right block mb-1 mt-3 text-xs font-semibold text-gray-600 dark:text-white"
                >{{ t("account.company.companyName") }}</label
            >
            <div class="border border-gray-300 rounded-lg mb-3">
                <input
                    v-if="selectedCompany"
                    type="text"
                    name="editName"
                    :placeholder="t('account.company.companyName')"
                    class="w-full px-1 py-1 rounded-lg text-right focus:outline-none h-9"
                    :value="selectedCompany[0]?.name"
                    @input="handleChangeCompanyName"
                />
            </div>
            <div v-if="showErrorEditName" class="text-red-600 text-xs">
                {{ t("validation.companyName.required") }}
            </div>
            <label
                class="text-right block mb-1 mt-3 text-xs font-semibold text-gray-600 dark:text-white"
                >{{ t("account.company.companyAddress") }}</label
            >
            <div class="border border-gray-300 rounded-lg mb-3">
                <input
                    v-if="selectedCompany"
                    type="text"
                    name="editAddress"
                    :placeholder="t('account.company.companyAddress')"
                    class="w-full px-1 py-1 rounded-lg text-right focus:outline-none h-9"
                    :value="selectedCompany[0]?.address"
                    @input="handleChangeCompanyAddress"
                />
            </div>
            <label
                class="text-right block mb-1 mt-3 text-xs font-semibold text-gray-600 dark:text-white"
                >{{ t("account.company.commercialNumber") }}</label
            >
            <div class="border border-gray-300 rounded-lg mb-3">
                <input
                    v-if="selectedCompany"
                    type="text"
                    name="editCommercialNumber"
                    :placeholder="t('account.company.commercialNumber')"
                    class="w-full px-1 py-1 rounded-lg text-right focus:outline-none h-9"
                    :value="selectedCompany[0]?.commercial_number"
                    @input="handleChangeCommercialNumber"
                />
            </div>
            <label
                class="text-right block mb-1 mt-3 text-xs font-semibold text-gray-600 dark:text-white"
                >{{ t("account.company.taxNumber") }}</label
            >
            <div class="border border-gray-300 rounded-lg mb-3">
                <input
                    v-if="selectedCompany"
                    type="text"
                    name="editCommercialNumber"
                    :placeholder="t('account.company.taxNumber')"
                    class="w-full px-1 py-1 rounded-lg text-right focus:outline-none h-9"
                    :value="selectedCompany[0]?.tax_number"
                    @input="handleChangeTaxNumber"
                />
            </div>
            <div v-if="showErrorEditAddress" class="text-red-600 text-xs">
                {{ t("validation.companyAddress.required") }}
            </div>
            <div v-if="errorMessage">
                <p class="text-red-500 text-sm">{{ errorMessage }}</p>
            </div>
            <div v-if="successMessage">
                <p class="text-green-500 text-sm">{{ successMessage }}</p>
            </div>
        </Form>
    </card-box-modal>
    <div v-if="company == null">
        <div class="sm:min-w-[300px] sm:max-w-[450px]">
            <div>
                <div
                    class="relative flex flex-col border rounded-md p-4 sm:mt-10"
                >
                    <span
                        class="absolute top-0 right-2 -translate-y-1/2 bg-white px-2"
                        >{{ t("account.company.panelTitle") }}</span
                    >
                    <label
                        class="pt-8 block text-right text-blueGray-600 text-sm font-bold mb-2"
                        >{{ t("account.company.companyLogo") }}</label
                    >
                    <div class="mb-3  text-center flex justify-center">
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
                    <div
                        v-if="showErrorImage"
                        class="text-red-600 text-xs text-right mb-5"
                    >
                        {{ t("validation.companyLogo.required") }}
                    </div>
                    <div class="text-right">
                        <label
                            for="name"
                            class="font-readex text-sm font-medium"
                        >
                            {{ t("account.company.companyName") }}
                        </label>
                        <div
                            class="border border-gray-300 rounded-lg mb-1 text-xs"
                        >
                            <input
                                type="text"
                                id="name"
                                name="name"
                                v-model="form.name"
                                :class="{ 'border-red-500': showErrorName }"
                                placeholder=""
                                class="w-full px-2 py-2 rounded-lg text-right focus:outline-none h-9 required"
                            />
                        </div>
                        <div v-if="showErrorName" class="text-red-600 text-xs">
                            {{ t("validation.companyName.required") }}
                        </div>
                    </div>
                    <div class="text-right mt-5">
                        <label
                            for="address"
                            class="font-readex text-sm font-medium"
                        >
                            {{ t("account.company.companyAddress") }}
                        </label>
                        <div class="border border-gray-300 rounded-lg mb-1">
                            <input
                                type="text"
                                id="address"
                                name="address"
                                v-model="form.address"
                                :class="{ 'border-red-500': showErrorAddress }"
                                placeholder=""
                                class="w-full px-1 py-1 rounded-lg text-right focus:outline-none h-9 required"
                            />
                        </div>
                        <div
                            v-if="showErrorAddress"
                            class="text-red-600 text-xs"
                        >
                            {{ t("validation.companyAddress.required") }}
                        </div>
                    </div>
                    <div class="text-right mt-5">
                        <label
                            for="commercialNumber"
                            class="font-readex text-sm font-medium"
                        >
                            {{ t("account.company.commercialNumber") }}
                        </label>
                        <div class="border border-gray-300 rounded-lg mb-1">
                            <input
                                type="text"
                                id="commercialNumber"
                                name="commercialNumber"
                                v-model="form.commercialNumber"
                                placeholder=""
                                class="w-full px-1 py-1 rounded-lg text-right focus:outline-none h-9"
                            />
                        </div>
                    </div>
                    <div class="text-right mt-5">
                        <label
                            for="taxNumber"
                            class="font-readex text-sm font-medium"
                        >
                            {{ t("account.company.taxNumber") }}
                        </label>
                        <div class="border border-gray-300 rounded-lg mb-1">
                            <input
                                type="text"
                                id="taxNumber"
                                name="taxNumber"
                                v-model="form.taxNumber"
                                placeholder=""
                                class="w-full px-1 py-1 rounded-lg text-right focus:outline-none h-9"
                            />
                        </div>
                    </div>

                    <div v-if="errorMessage">
                        <p class="text-red-500 text-sm">{{ errorMessage }}</p>
                    </div>
                    <div v-if="successMessage">
                        <p class="text-green-500 text-sm">
                            {{ successMessage }}
                        </p>
                    </div>
                    <div class="w-full mt-16">
                        <button
                            @click="onSubmit()"
                            class="flex px-6 py-3 justify-center items-center bg-brandGreen w-fit rounded-lg"
                        >
                            <span class="font-readex text-sm text-white">
                                {{ t("common.save") }}
                            </span>
                            <BaseIcon
                                :path="mdiCheck"
                                class="ml-3 text-white"
                            />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-else>
        <div
            class="relative flex flex-col border rounded-md p-4 sm:mt-10 sm:min-w-[300px] sm:max-w-[450px]"
        >
            <span
                class="absolute top-0 right-2 -translate-y-1/2 bg-white px-2"
                >{{ t("account.company.panelTitle") }}</span
            >
            <div class="mb-3 pt-8 text-center">
                <label
                    class="block uppercase text-blueGray-600 text-sm font-bold mb-2"
                    for="profile-picture"
                    >{{ t("account.company.companyLogo") }}</label
                >
            </div>
            <div class="mb-3 pt-0 text-center mx-auto">
                <img
                    v-if="company"
                    :src="
                        `/storage/tenant` +
                        subDomain +
                        `/app/public/` +
                        company[0]?.logo
                    "
                    class="w-1/4 h-2/4 mx-auto"
                    alt="Profile Picture"
                />
            </div>

            <div class="text-right">
                <label for="name" class="font-readex text-sm font-medium">
                    {{ t("account.company.companyName") }}
                </label>
                <div class="border border-gray-300 rounded-lg mb-1 text-xs">
                    <div
                        v-if="company"
                        class="w-full px-2 py-2 rounded-lg text-right focus:outline-none h-9 text-sm"
                    >
                        {{ company[0]?.name }}
                    </div>
                </div>
            </div>
            <div class="text-right mt-5">
                <label for="address" class="font-readex text-sm font-medium">
                    {{ t("account.company.companyAddress") }}
                </label>
                <div class="border border-gray-300 rounded-lg mb-1">
                    <div
                        v-if="company"
                        class="w-full px-2 py-2 rounded-lg text-right focus:outline-none h-9 text-sm"
                    >
                        {{ company[0]?.address }}
                    </div>
                </div>
            </div>
            <div class="text-right mt-5">
                <label for="commercialNumber" class="font-readex text-sm font-medium">
                    {{ t("account.company.commercialNumber") }}
                </label>
                <div class="border border-gray-300 rounded-lg mb-1">
                    <div
                        v-if="company"
                        class="w-full px-2 py-2 rounded-lg text-right focus:outline-none h-9 text-sm"
                    >
                        {{ company[0]?.commercial_number }}
                    </div>
                </div>
            </div>
            <div class="text-right mt-5">
                <label for="taxNumber" class="font-readex text-sm font-medium">
                    {{ t("account.company.taxNumber") }}
                </label>
                <div class="border border-gray-300 rounded-lg mb-1">
                    <div
                        v-if="company"
                        class="w-full px-2 py-2 rounded-lg text-right focus:outline-none h-9 text-sm"
                    >
                        {{ company[0]?.tax_number }}
                    </div>
                </div>
            </div>
            <div class="w-full mt-16">
                <button
                    @click="() => handleClickEdit(company)"
                    class="flex px-6 py-3 justify-center items-center bg-blue-500 w-fit rounded-lg"
                >
                    <span class="font-readex text-sm text-white">
                        {{ t("common.edit") }}
                    </span>
                    <BaseIcon :path="mdiPencil" class="ml-3 text-white" />
                </button>
            </div>
        </div>
    </div>
</template>
