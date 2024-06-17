import { defineStore } from "pinia";

export const useCartsStore = defineStore('carts', {
  state: () => ({
    carts: [],
    selectedCart: null,
    note: '',
    discount: 0,
    /**
     {
       pieceTitle,
       pieceId,
       service,
       count,
       discount,
       note
     }
     */

  }),

  actions: {
    clear() {
      this.carts = [],
      this.note = '',
      this.discount = 0;
    },
    
    setCarts(payload) {

    },

    addNewCart(payload) {
      const _carts = this.carts.filter((c) => c.service.id === payload.service.id)
      if (_carts.length) {
        console.log('already exist')
      } else {
        this.carts.push(payload);
      }
    },

    removeCart(payload) {
      const _cart = this.carts.find((c) => c.service.id === payload.service.id)
      if (_cart) {
        const index = this.carts.indexOf(_cart);
        if (index > -1) { // only splice array when item is found
          this.carts.splice(index, 1); // 2nd parameter means remove one item only
        }
      }
    },

    changeCartCount(cart, count) {
      console.log("This.Carts", this.carts,"cart.service.id", cart.service.id)
      const _cart = this.carts.find((c) => c.service.id === cart.service.id)
      if (_cart) {
        _cart.count += count 
      }
    },

    changeCartDiscount(cart, discount) {
      const _cart = this.carts.find((c) => c.service.id === cart.service.id)
      if (_cart) {
        _cart.discount = parseFloat(discount) 
      }
    },

    changeCartNote(cart, note) {
      console.log("Note", cart, note)
      const _cart = this.carts.find((c) => c.service.id === cart.service.id)
      if (_cart) {
        _cart.note = note
      }
    },

    updateDiscount(discount) {
      this.discount = discount
    },

    updateNote(note) {
      this.note = note
    },
  }
})