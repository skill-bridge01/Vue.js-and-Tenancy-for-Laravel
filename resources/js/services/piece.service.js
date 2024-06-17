import { http } from './http'
import router from '../router'

class PieceService {
  apiUrl = '/pieces'
  create(title) {
    return http.post(this.apiUrl, {
      title
    })
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

  edit(pieceId, title) {
    return http.put(this.apiUrl + '/' + pieceId, {
      title
    })
  }

  delete(pieceId) {
    return http.delete(this.apiUrl + '/' + pieceId)
  }
}

export const pieceService = new PieceService()
