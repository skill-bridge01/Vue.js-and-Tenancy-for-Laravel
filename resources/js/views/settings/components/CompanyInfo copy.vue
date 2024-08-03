<script setup>
import FileUpload from "primevue/fileupload";
import { ref } from "vue";
import axios from "axios";
// Define refs for form fields
const showSuccessMessage = ref(false);
const showErrorMessage = ref(false);
const errorMessage = ref("");
const showErrorName = ref(false);
const showErrorAddress = ref(false);
const showErrorPassword = ref(false);
const previewImage = ref("");
const form = ref({
    name: "",
    address: "",
    profile_picture: "",
});
const resetForm = () => {
    form.value = {
        name: "",
        address: "",
        profile_picture: "",
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

const validateAndRegister = async () => {
    console.log('formData');
    
    try {
        const formData = new FormData();
        for (const key in form.value) {
            formData.append(key, form.value[key]);
        }
        // const response = await axios.post("/api/company-info", formData, {
        //     headers: {
        //         "Content-Type": "multipart/form-data",
        //     },
        // });
        console.log('formData', formData);
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
    <div>
        <div>
            <!-- <FileUpload
                name="demo"
                url="/api/upload"
                @upload="onAdvancedUpload($event)"
                :multiple="true"
                accept="image/*"
                :maxFileSize="1000000"
            >
                <template #empty>
                    <span>Drag and drop files to here to upload.</span>
                </template>
            </FileUpload> -->
            <form @submit.prevent="validateAndRegister">
                <div class="mb-3 pt-0 text-center">
                    <label
                        class="block uppercase text-blueGray-600 text-xs font-bold mb-2"
                        for="name"
                        >Name:</label
                    >
                    <input
                        type="text"
                        placeholder="Name"
                        id="name"
                        v-model="form.name"
                        :class="{ 'border-red-500': showErrorName }"
                        class="px-2 py-1 placeholder-blueGray-300 text-blueGray-600 relative bg-white rounded text-sm border border-blueGray-300 outline-none focus:outline-none focus:ring w-1/2 required"
                    />
                    <div v-if="showErrorName" class="text-red-600 text-sm mt-1">
                        Name is required
                    </div>
                </div>
                <div class="mb-3 pt-0 text-center">
                    <label
                        class="block uppercase text-blueGray-600 text-xs font-bold mb-2"
                        for="address"
                        >Address:</label
                    >
                    <input
                        type="address"
                        id="address"
                        v-model="form.address"
                        :class="{ 'border-red-500': showErrorAddress }"
                        placeholder="Address"
                        class="px-2 py-1 placeholder-blueGray-300 text-blueGray-600 relative bg-white rounded text-sm border border-blueGray-300 outline-none focus:outline-none focus:ring w-1/2 required"
                    />
                    <div
                        v-if="showErrorAddress"
                        class="text-red-600 text-sm mt-1"
                    >
                        Address is required
                    </div>
                </div>
                <div class="mb-3 pt-0 text-center">
                    <label
                        class="block uppercase text-blueGray-600 text-xs font-bold mb-2"
                        for="profile-picture"
                        >Profile Picture:</label
                    >
                    <input
                        type="file"
                        id="profile-picture"
                        ref="profilePicture"
                        class="w-1/2"
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
                <button
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                    Sign Up
                </button>
            </form>
        </div>
    </div>
</template>
