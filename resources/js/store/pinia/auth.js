import { defineStore } from "pinia";
import { authService } from "../../services";

export const useAuthStore = defineStore('auth', {
  state: () => ({
    username: null,
    userEmail: null,
    userAvatar: null,
    user: null,
    company: null,
    selectedCompany: null,
    token: null,
    role:null,
  }),
  actions: {
    clear() {
      this.user = null
      this.token = null
    },
    setAuthUser(role, email) {
      if (email) {
        this.userEmail = email
      }
      if (role) {
        this.role = role
      }
    },

    setSelectedCompany(payload) {
      console.log(payload);
      this.selectedCompany = payload;
      // if (payload) {
      //     this.pieceSelected = true;
      // } else {
      //     this.pieceSelected = false;
      // }
  },

    setToken(token) {
      if (token) {
        this.token = token
        localStorage.setItem('laundry-token', token)
      } else {
        this.token = null
        localStorage.removeItem('laundry-token')
      }
    },

    fetchCompany() {
      authService.getCompany().then(company => {
        console.log('CompanyInfo',company)
        if (company!=0) {
          this.company = company
        }
        
      }).catch((err) => {
        console.log(err)
      })
    },



    fetchUser() {
      authService.getMe().then(user => {
        console.log(user)
        if (user) {
          this.user = user
        }
        
      }).catch((err) => {
        console.log(err)
      })
    }


  }
})