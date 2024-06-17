import axios from 'axios'
import {
    requestInterceptor,
    responseInterceptor,
    errorInterceptor,
} from '@/services/http/interceptors'

class HttpService {
    constructor(apiUrl) {
        this.apiUrl = apiUrl
        this.createAxiosInstance()
        this.registerInterceptors()
    }

    createAxiosInstance() {
        this.axios = axios.create()
    }

    registerInterceptors() {
        this.axios.interceptors.response.use(
            (res) => responseInterceptor(res),
            (err) => errorInterceptor(err)
        )

        this.axios.interceptors.request.use(requestInterceptor)
    }

    get(url, config) {
        return this.axios.get(this.apiUrl + url, config)
    }

    put(url, payload, config) {
        return this.axios.put(this.apiUrl + url, payload, config)
    }

    post(url, payload, config) {
        return this.axios.post(this.apiUrl + url, payload, config)
    }

    patch(url, payload, config) {
        return this.axios.patch(this.apiUrl + url, payload, config)
    }

    delete(url, config) {
        return this.axios.delete(this.apiUrl + url, config)
    }
}

export const http = new HttpService('/api')
