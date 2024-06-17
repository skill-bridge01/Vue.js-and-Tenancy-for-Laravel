import { defineStore } from 'pinia'
import { priceService } from '@/services'
import axios from "axios";

export const usePricesStore = defineStore('price', {
    state: () => ({
      selectedPrice: null,
      pieceSelected: false,
      prices: [],
      pieceFetchError: null,
      loading: false,
    }),
    actions: {
      clear() {
        this.selectedPrice = null;
        this.pieceSelected = false;
        this.loading = false;
      },
      setSelectedPrice(payload) {
        console.log(payload)
        this.selectedPrice = payload
        if (payload) {
            this.pieceSelected = true
        } else {
            this.pieceSelected = false
        }
        // payload ? this.pieceSelected = true : this.pieceSelected = false;
      },

      fetch() {
        this.loading = true;
        priceService.getList().then((res) => {
          this.prices = res.data
        }).catch((err) => {
          this.pieceFetchError = err
        }).finally(() => {
          this.loading = false;
        })
      },

      fetchLocalData() {
        axios
          .get(`data-sources/pieces.json`, { baseURL: window.location.origin})
          .then((r) => {
            console.log(r.data)
            this.prices = r.data
          })
      },

      async create(service, piece, price) {
        this.loading = true;
        try {
          const res = await priceService.create(service, piece, price);
          console.log('@#@#', res);
          // this.prices.push(res.data)
          await this.fetch()
          console.log('@#@#1111', res);
          return res.data;
        } catch (error) {
          this.pieceFetchError = error
          return error       
        } finally {
          this.loading = false;
        }
      },

      async edit(pieceId, service, piece, price) {
        this.loading = true;
        try {
          const res = await priceService.edit(pieceId, service, piece, price);
          console.log('@#@#@@@', res);
          const pieceIndex = this.prices.findIndex(p => p.id === pieceId)
          // this.prices[pieceIndex] = res.data
          await this.fetch()
          return res.data;
        } catch (error) {
          this.pieceFetchError = error
          return error
        } finally {
          this.loading = false;
        }
      },
      async delete(pieceId) {
        this.loading = true;
        try {
          await priceService.delete(pieceId);
          this.prices = this.prices.filter(p => p.id !== pieceId)
          return true
        } catch (error) {
          this.pieceFetchError = error
          return error
        } finally {
          this.loading = false;
        }
      }
    },
})
