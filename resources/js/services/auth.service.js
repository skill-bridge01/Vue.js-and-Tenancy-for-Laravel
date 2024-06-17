import { http } from "./http";
import router from "../router";
import { useAuthStore } from "@/store/pinia/auth";
import { storeToRefs } from "pinia";

class AuthService {
    async login(payload) {
        const userAuthStore = useAuthStore();
        const { prices } = storeToRefs(useAuthStore());
        // try {
        const response = await http.post("/signin", payload);
        console.log("User", response.data.data.user.role);
        userAuthStore.setAuthUser(
            response.data.data.user.role,
            response.data.data.user.email
        );

        return response.data.data;
        // } catch (error) {
        //   console.log(error)
        //   return Promise.reject(error)
        // }
    }

    async phoneVerify1(phone) {
        const response = await http.post("/phone-verify", phone);
        console.log('Verify111',response);
        return response.data;
    }

    phoneVerify2(phone) {
        return http.post("/phone-verify", {
            phone
        });
    }

    async signup(payload) {
        const response = await http.post("/signup", payload);
        console.log(response);
        return response.data;
    }

    async getMe() {
        const response = await http.post("/me");
        return response.data;
    }

    async sendVerificationEmail(payload) {
        const response = await http.post("/send-verification-email", payload);
        return response.data;
    }

    async generateQRCode(payload) {
        const response = await http.post("/qr-code", payload);
        return response.data;
    }

    async updatePassword(payload) {
        const response = await http.post("/update-password", payload);
        return response.data;
    }
}

export const authService = new AuthService();
