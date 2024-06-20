import { defineStore } from "pinia";
import { userAction } from "../../services/user.service";

export const useUsersStore = defineStore("user", {
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
            console.log("SelectedUser", payload);
            if (payload) {
                this.selectedService = payload;
                this.serviceSelected = true;
            } else {
                this.serviceSelected = false;
            }
        },

        fetch() {
            this.loading = true;
            userAction
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

        async create(email, username, password) {
            this.loading = true;
            try {
                const res = await userAction.create(email, username, password);
                if (res.data.user) {
                    this.services.push(res.data.user);
                }

                return res.data;
            } catch (error) {
                this.fetchError = error;
                return error;
            } finally {
                this.loading = false;
            }
        },

        async edit(serviceId, newUsername, oldPassword, password) {
            console.log(
                "eeew",
                this.services,
                serviceId,
                newUsername,
                oldPassword,
                password
            );
            this.loading = true;
            try {
                const res = await userAction.edit(
                    serviceId,
                    newUsername,
                    oldPassword,
                    password
                );
                if (res.data.success) {
                    const serviceIndex = this.services.findIndex(
                        (s) => s.id === serviceId
                    );
                    this.services[serviceIndex] = res.data.user;
                }
                return res.data;
            } catch (error) {
                this.fetchError = error;
                return error;
            } finally {
                this.loading = false;
            }
        },

        async edit1(serviceId, newUsername) {
            console.log(
                "eeew",
                this.services,
                serviceId,
                newUsername,
               
            );
            this.loading = true;
            try {
                const res = await userAction.edit1(
                    serviceId,
                    newUsername,
                    
                );
                if (res.data.success) {
                    const serviceIndex = this.services.findIndex(
                        (s) => s.id === serviceId
                    );
                    this.services[serviceIndex] = res.data.user;
                }
                return res.data;
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
                await userAction.delete(serviceId);
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
