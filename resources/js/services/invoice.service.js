import { http } from './http'
import router from '../router'

class InvoiceService {
    create(data) {
      return http.post('/create-invoice', data)
    }

    update(data) {
      return http.post('/update-invoice', data)
    }

    getUserInvoices() {
      return http.post('/get-user-invoices')
    }

    getInvoicesFile(data) {
      return http.post('/get-invoices-file',data, { responseType: 'blob'})
    }
    delete(invoiceId) {
      return http.delete('/invoice/' + invoiceId)
    }
}

export const invoiceService = new InvoiceService()
