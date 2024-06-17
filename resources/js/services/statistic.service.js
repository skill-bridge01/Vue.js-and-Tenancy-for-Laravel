import { http } from './http'
import router from '../router'

class StatisticService {

    getStatistics(query) {
      return http.post('/get-statstices/', {
        query
      })
    }

    getYear() {
      return http.post('/get-statstices/', {
        query: 'year'
      })
    }

    getMonth() {
      return http.post('/get-statstices/', {
        query: 'month'
      })
    }
    
    getToday() {
      return http.post('/get-statstices/', {
        query: 'today'
      })
    }

    getLaundryInfo() {
      return http.post('/get-laundry-info')
    }
}

export const statisticService = new StatisticService()
