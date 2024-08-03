import { http } from "./http";
import router from "../router";

class PieceService {
    apiUrl = "/pieces";
    create(title) {
        return http.post(this.apiUrl, title, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
    }
    // getList() {
    //   return http.get('/piece-list')
    // }
    getList() {
        return http.get(this.apiUrl + "/");
    }
    // getList() {
    //   return http.get('/prices')
    // }

    edit(pieceId, formData) {
        return http.post("/pieces-edit/" + pieceId, formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
    }

    delete(pieceId) {
        return http.delete(this.apiUrl + "/" + pieceId);
    }
}

export const pieceService = new PieceService();
