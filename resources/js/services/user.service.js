import { http } from "./http";
import router from "../router";

class UserAction {
    apiUrl = "/users";
    create(email, username, password) {
        return http.post(this.apiUrl, {
            email,
            username,
            password,
        });
    }

    getList() {
        return http.get(this.apiUrl + "/");
    }

    edit(pieceId, newUsername, oldPassword, password) {
        return http.put(this.apiUrl + "/" + pieceId, {
            newUsername,
            oldPassword,
            password,
        });
    }
    edit1(pieceId, newUsername) {
        return http.put(this.apiUrl + "/" + pieceId, {
            newUsername,
        });
    }

    async updateUser(pieceId, newUsername, oldPassword, password) {
        const response = await http.put(this.apiUrl + "/" + pieceId, {
            newUsername,
            oldPassword,
            password,
        });
        return response.data;
    }

    delete(pieceId) {
        return http.delete(this.apiUrl + "/" + pieceId);
    }
}

export const userAction = new UserAction();
