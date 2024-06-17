// import router from '@/router'
import router from '../../../router'
import { useAuthStore } from '../../../store/pinia/auth'
import { storeToRefs } from 'pinia'

let invalidCredentials = false


export const errorInterceptor = async (error) => {
  const {token} = storeToRefs(useAuthStore())
  const { setToken, setUser} = useAuthStore()
  if (error.response?.status === 401) {
    if (token) {
      invalidCredentials = true
    }

    setToken(null)
    setUser(null)
    
    router.push({name: 'Login'}).then(() => {
      console.log('invalid login');
      if (invalidCredentials) {
        invalidCredentials = false
      }
    })
  }

  // global error handler
  return Promise.reject(error)
}