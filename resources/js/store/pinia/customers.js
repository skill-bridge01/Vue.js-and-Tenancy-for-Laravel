import { defineStore } from 'pinia'
import { customerService } from '@/services'

export const useCustomersStore = defineStore('customers', {
    state: () => ({
        customers: [],
        selectedCustomer: null,
    }),

    actions: {
        clear() {
            this.customers = []
            this.selectedCustomer = null
        },
        setActiveCustomer(payload) {
            this.selectedCustomer = payload
        },

        addNewCustomer(payload) {
          customerService
              .create(payload)
              .then((res) => {
                  console.log(res.data.customer)
                  const customer = res.data.customer
                  this.customers.push(customer)
              })
              .catch((err) => {
                  console.log(err)
              })
        },

        fetch() {
          customerService
              .search()
              .then((res) => {
                  console.log(res.data)
                  if (res.data.status) {
                      this.customers = res.data.customer
                  }
              })
              .catch((err) => {
                  console.log(err)
              })
        },

        async search(keyword) {
          try {
            const res = await customerService.search({ keyword })
            console.log(res.data.status)
            if (res.data.status) {
              console.log(res.data)

              this.customers = res.data.customer
              return res.data.customer
            } else {
              return Promise.reject('Not found')
            }
          } catch (error) {
            return Promise.reject('')
          }
        },
    },
})
