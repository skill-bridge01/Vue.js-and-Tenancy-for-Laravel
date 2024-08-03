import { defineStore } from "pinia";
import { pieceService } from "@/services";
import axios from "axios";

export const usePiecesStore = defineStore("piece", {
    state: () => ({
        selectedPiece: null,
        pieceSelected: false,
        pieces: [],
        pieceFetchError: null,
        loading: false,
    }),
    actions: {
        clear() {
            this.selectedPiece = null;
            this.pieceSelected = false;
            this.loading = false;
        },
        setSelectedPiece(payload) {
            console.log(payload.id);
            this.selectedPiece = payload;
            if (payload) {
                this.pieceSelected = true;
            } else {
                this.pieceSelected = false;
            }
        },

        fetch() {
            this.loading = true;
            pieceService
                .getList()
                .then((res) => {
                    this.pieces = res.data;
                })
                .catch((err) => {
                    this.pieceFetchError = err;
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        fetchLocalData() {
            axios
                .get(`data-sources/pieces.json`, {
                    baseURL: window.location.origin,
                })
                .then((r) => {
                    console.log(r.data);
                    this.pieces = r.data;
                });
        },

        async create(title) {
            this.loading = true;
            try {
                const res = await pieceService.create(title);
                if (res.data.piece) {
                    this.pieces.push(res.data.piece);
                }

                return res.data;
            } catch (error) {
                this.pieceFetchError = error;
                return error;
            } finally {
                this.loading = false;
            }
        },

        async edit(pieceId, formData) {
            this.loading = true;
            try {
                const res = await pieceService.edit(pieceId, formData);
                console.log('res-store', res)
                const pieceIndex = this.pieces.findIndex(
                    (p) => p.id === pieceId
                );
                this.pieces[pieceIndex] = res.data.piece;
                return res;
            } catch (error) {
                // this.pieceFetchError = error;
                // return error;
            } finally {
                this.loading = false;
            }
        },
        async delete(pieceId) {
            this.loading = true;
            try {
                await pieceService.delete(pieceId);
                console.log("pieces::", this.pieces, pieceId)
                this.pieces = this.pieces.filter((p) => p.id !== pieceId);
                return true;
            } catch (error) {
                this.pieceFetchError = error;
                return error;
            } finally {
                this.loading = false;
            }
        },
    },
});
