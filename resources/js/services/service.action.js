import { http } from './http'
import router from '../router'

class ServiceAction {
  apiUrl = '/services'
  create(services_title, is_checked, is_shown) {
    return http.post(this.apiUrl, {
      services_title, is_checked, is_shown
    })
  }
  getList() {
    return http.get(this.apiUrl + '/')
  }

  edit(serviceId, title, is_checked, is_shown) {
    return http.put(this.apiUrl + '/' + serviceId, {
      title, is_checked, is_shown
    })
  }

  delete(serviceId) {
    return http.delete(this.apiUrl + '/' + serviceId)
  }
}

export const serviceAction = new ServiceAction()
