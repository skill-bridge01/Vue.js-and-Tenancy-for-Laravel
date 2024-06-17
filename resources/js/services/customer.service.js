import { http } from './http'

class CustomerService {
    create(data) {
      return http.post('/create-customer', data)
    }

    update(data) {
      return http.post('/update-invoice', data)
    }

    getAll() {
      return http.post('/get-customers')
    }

    search(data) {
      return http.post('/search-customer',data)
    }
}

export const customerService = new CustomerService()