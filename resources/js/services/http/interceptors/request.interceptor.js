import { useAuthStore } from '../../../store/pinia/auth'

export const requestInterceptor = async (requestConfig) => {
  // const token = '51|pY2qTDoPZ3AjaXtMRGgV6FPrtHsy8YF4tof4vTtC'
  const token = localStorage.getItem('laundry-token')
  if (token) {
    requestConfig.headers.Authorization = `Bearer ${token}`
  } else {
    requestConfig.headers.Authorization = `Bearer ${useAuthStore().token}`
  }

  return requestConfig
}