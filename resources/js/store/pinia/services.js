import { defineStore } from "pinia";
import { serviceAction } from "../../services/service.action";

export const useServicesStore = defineStore("service", {
    state: () => ({
        orderedServices: [],
        selectedService: null,
        serviceSelected: false,
        services: [],
        fetchError: null,
        loading: false,
    }),
    actions: {
        clear() {
            this.orderedServices = [];
            this.selectedService = null;
            this.loading = false;
        },

        setSelectedService(payload) {
            this.selectedService = payload;
            if (payload) {
                this.serviceSelected = true;
            } else {
                this.serviceSelected = false;
            }
        },

        fetch() {
            this.loading = true;
            serviceAction
                .getList()
                .then((res) => {
                    this.services = res.data;
                })
                .catch((err) => {
                    this.fetchError = err;
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        async create(services_title, is_checked, is_shown) {
            this.loading = true;
            try {
                const res = await serviceAction.create(
                    services_title,
                    is_checked,
                    is_shown
                );
                if (res.data.service) {
                    this.services.push(res.data.service);
                }
                return res.data;
            } catch (error) {
                this.fetchError = error;
                return error;
            } finally {
                this.loading = false;
            }
        },

        async edit(serviceId, title, is_checked, is_shown) {
            console.log("eeew", serviceId, title, is_checked, is_shown);
            this.loading = true;
            try {
                const res = await serviceAction.edit(
                    serviceId,
                    title,
                    is_checked,
                    is_shown
                );
                const serviceIndex = this.services.findIndex(
                    (s) => s.id === serviceId
                );
                this.services[serviceIndex] = res.data;
                return true;
            } catch (error) {
                this.fetchError = error;
                return error;
            } finally {
                this.loading = false;
            }
        },

        async delete(serviceId) {
            this.loading = true;
            try {
                await serviceAction.delete(serviceId);
                this.services = this.services.filter((s) => s.id !== serviceId);
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
