import { http } from "./http";
import router from "../router";

class PriceService {
    apiUrl = "/prices";
    create(service_id, piece_id, price) {
        return http.post(this.apiUrl, {
            service_id,
            piece_id,
            price,
        });
    }
    getList() {
        return http.get(this.apiUrl + "/");
    }

    edit(pieceId, service_id, piece_id, price) {
        return http.put(this.apiUrl + "/" + pieceId, {
            service_id, piece_id, price,
        });
    }

    delete(pieceId) {
        return http.delete(this.apiUrl + "/" + pieceId);
    }
}

export const priceService = new PriceService();
