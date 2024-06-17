import { defineStore } from "pinia";
import { statisticService } from '@/services'

export const useStatisticStore = defineStore('statistics', {
  state: () => ({
    yearData: null,
    monthData: null,
    dayData: null,
    error: null,
    loading: false,
    currentTabIndex: 0,
    totalSales: [],
    mostPurchasedCustomerArr: [],
    mostServicedItems: [],
    mostServices: [],
  }),

  actions: {
    clear() {
      this.yearData = null;
      this.monthData = null;
      this.dayData = null;
    },

    setLoading(value) {
      this.loading = value;
    },

    setYearData(payload) {
      this.yearData = payload;
    },

    setMonthData(payload) {
      this.monthData = payload;
    },

    setDayData(payload) {
      this.dayData = payload;
    },

    setCurrentTabIndex(payload) {
      this.currentTabIndex = payload;
    },

    setData(payload) {
      this.totalSales = payload['total_sales'];
      this.mostPurchasedCustomerArr = payload['most_purchased_customers'];
      this.mostServicedItems = payload['most_serviced_items'];
      this.mostServices = payload['most_services'];
    },

    fetch(query = 'day') {
      this.setLoading(true);

      statisticService.getStatistics(query).then((res) => {
        const { status, cards } = res.data;
        if (status) {
          this.setData(cards);
          switch (query) {
            case "day":
              this.setDayData(cards);
              break;
            case "month":
              this.setMonthData(cards);
              break;
            case "year":
              this.setYearData(cards);
              break;        
            default:
              break;
          }
          
        } else {

        }

      }).catch((err) => {
        console.log(err);
      })
    }
  }
})