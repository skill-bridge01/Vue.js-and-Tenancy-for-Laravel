import { defineStore } from "pinia";
import { invoiceService } from "@/services";
import axios from "axios";

export const useInvoicesStore = defineStore("invoices", {
    state: () => ({
        invoices: [],
        filteredInvoices: [],
        selectedInvoice: null,
        createdInvoice: null,
        fetchError: null,
        loading: false,
    }),

    actions: {
        clear() {
            this.invoices = [];
            this.filteredInvoices = [];
            this.selectedInvoice = null;
            this.createdInvoice = null;
            this.fetchError = null;
            this.loading = false;
        },
        setInvoices(payload) {
            this.invoices = payload;
        },

        setFilteredInvoices(payload) {
            this.filteredInvoices = payload;
        },

        setActiveInvoice(payload) {
            this.selectedInvoice = payload;
        },

        fetch() {
            this.loading = true;
            invoiceService
                .getUserInvoices()
                .then((res) => {
                    this.invoices = res.data["invoices"];
                    this.filteredInvoices = res.data["invoices"];
                    this.loading = false;
                })
                .catch((err) => {
                    this.fetchError = err;
                    this.loading = false;
                });
        },

        fetchLocalData() {
            axios
                .get(`data-sources/invoices.json`, {
                    baseURL: window.location.origin,
                })
                .then((r) => {
                    console.log(r.data["status"]);
                    if (r.data.status) {
                        console.log(r.data["invoices"]);
                        this.invoices = r.data["invoices"];
                        this.filteredInvoices = r.data["invoices"];
                    }
                });
        },

        async createInvoice(carts, note, discount, customer, paymentMethod) {
            console.log("payload");
            const payload = makeBodyData({
                carts,
                discount,
                note,
                customer,
                paymentMethod,
            });
            console.log("payload");
            const res = await invoiceService.create(payload);
            if (res.data.status) {
                return res.data.invoice;
            } else {
                return Promise.reject("error occurred");
            }
        },

        async downloadInvoice(invoiceId, baseURL) {
            try {
                const res = await invoiceService.getInvoicesFile({
                    invoice_id: invoiceId,
                    baseURL: baseURL,
                    pdf: true,
                });
                return res.data;
            } catch (error) {
                return Promise.reject(error);
            }
        },

        async updateInvoice(data) {
            try {
                const res = await invoiceService.update(data);
                console.log("invoice Info", res.data);
                if (res.data.status) {
                    return res.data.invoice;
                } else {
                    return Promise.reject("");
                }
            } catch (error) {
                return Promise.reject(error);
            }
        },
        async delete(data) {
            this.loading = true;
            try {
                await invoiceService.delete(data);
                console.log("Invoices::", this.filteredInvoices, data)
                this.filteredInvoices = this.filteredInvoices.filter((p) => p.id !== data);
                return true;
            } catch (error) {
                this.fetchError = error;
                return error;
            } finally {
                this.loading = false;
            }
        },
    },
});

function makeBodyData(payload) {
    let data = {};
    data["discount"] = payload.discount;
    data["note"] = payload.note;
    data["customer_name"] = payload.customer.name;
    data["mobile"] = payload.customer.mobile;
    data["customer_id"] = payload.customer.id;
    data["payment_method"] = payload.paymentMethod;

    const piece_data = payload.carts.map((c, index) => {
        return {
            id: c.service.pivot.id,
            number_of_piece: c.count,
            note: c.note,
            discount: c.discount,
        };
    });
    data["service_piece_ids"] = piece_data;
    console.log(data);
    return data;
}
