<script setup>
import { computed, ref } from "vue";
import OverlayLayer from "@/components/OverlayLayer.vue";
import { useCartsStore } from '@/store/pinia/carts'
import { useCustomersStore } from '@/store/pinia/customers'
import { useInvoicesStore } from '@/store/pinia/invoices'
import { storeToRefs } from 'pinia';
import printJS from "print-js";
import { useI18n } from "vue-i18n";

import {
  format,
} from 'date-fns'

const props = defineProps({
  modalActive: Boolean,
})

const emits = defineEmits([
  'toggleModalActive',
  'downloadPdf'
])
const { t } = useI18n();
const html2Pdf = ref(null)
const customersStore = useCustomersStore()
const cartsStore = useCartsStore()
const { carts, selectedCart } = storeToRefs(useCartsStore())
const invoicesStore = useInvoicesStore()
const { selectedInvoice } = storeToRefs(useInvoicesStore())

const active = computed({
  get() {
    return props.modalActive
  },
  set(val) {
    emits('toggleModalActive', val)
  }
})

const totalPrice = computed(() => {
  let total = 0
  carts.value.forEach(cart => {
    total += cart.service.pivot.price * cart.count
  });

  return total
})
// const totalDiscount = computed(() => {
//   let total = 0
//   carts.value.forEach(cart => {
//     total += cart.discount
//   });

//   return total
// });

const confirmCancel = () => {
  emits('toggleModalActive', false)
};
const cancel = () => confirmCancel();
const handleClickDownload = () => {
  downloadPdf()
}

const handleClickPrint = () => {
  printPdf()
}

const printPdf = () => {
  invoicesStore.downloadInvoice(selectedInvoice.value.id, window.location.origin).then((res) => {
    const url = window.URL.createObjectURL(new Blob([res], {type: 'application/pdf'}));
    printJS(url)
  }).catch((err) => {
    console.log(err)
  })
}

const downloadPdf  = () => {
  invoicesStore.downloadInvoice(selectedInvoice.value.id, window.location.origin).then((res) => {
    const url = window.URL.createObjectURL(new Blob([res], {type: 'application/pdf'}));
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', 'invoice.pdf');
    document.body.appendChild(link);
    link.click();
  }).catch((err) => {
    console.log(err)
  })
}

</script>
<template>
  <OverlayLayer v-show="active" @overlay-click="cancel">
    <div class="invoice-container z-50 overflow-scroll">
      <div class="invoice-wrapper mt-36 shadow-lg w-11/12 md:w-3/5 lg:w-2/5 xl:w-4/12 z-50 dark:bg-slate-900"
        v-show="active" :class="active ? '' : 'hidden'">

        <div class="content-wrapper bg-white">
          <div class="company-header">
            <div class="company-name">
              {{ t('invoices.pdf.companyName') }}
              <br>
              {{ t('invoices.pdf.address') }}
              <p>+966 65489415</p>
            </div>
            <div class="company-logo">
              <img :src="`/images/home/company_logo.svg`" alt="logo" />
            </div>
          </div>
          <div class="invoice-title-wrapper">
            {{ t('invoices.pdf.invoice') }}
            <br />
            INV-{{ selectedInvoice.dailyId }}
          </div>
          <div class="line">
          </div>
          <div class="customer-info">
            <p class="flex gap-1 items-center justify-end"><span>{{ selectedInvoice?.customerName || 'أحمد أحمد'
            }}:</span><span>الزبون</span></p>
            <p><span>{{ selectedInvoice.mobile || '+966 45648153' }}</span> : <span>رقم الهاتف</span></p>
          </div>
          <div class="invoice-table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>الإجمالي</th>
                  <th>الخدمة</th>
                  <th>العدد</th>
                  <th>نوع القطعة</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(service, index) in selectedInvoice?.services" :key="index">
                  <td><span>ر.س</span> <span>{{ service.price }}</span></td>
                  <td>{{ service.serviceinfo.services_title }}</td>
                  <td>{{ service.pivot.number_of_piece }}</td>
                  <td>{{ '' }}</td>
                </tr>
                <tr v-if="selectedInvoice.totalPrice">
                  <td>
                    <span>ر.س</span> <span>{{ selectedInvoice.totalPrice.toFixed(2)}}</span>
                  </td>
                  <td></td>
                  <td></td>
                  <td>المجموع</td>
                </tr>
                <tr v-if="selectedInvoice.totalDiscount">
                  <td><span>ر.س</span> <span>{{ selectedInvoice.totalDiscount.toFixed(2) }}</span></td>
                  <td></td>
                  <td></td>
                  <td>الخصم</td>
                </tr>
                <tr v-if="selectedInvoice.totalPrice && selectedInvoice.totalDiscount">
                  <td><span>ر.س</span> <span>{{ (selectedInvoice.totalPrice - selectedInvoice.totalDiscount).toFixed(2) }}</span></td>
                  <td></td>
                  <td></td>
                  <td>المجموع الكلي</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="note-wrapper" v-if="selectedInvoice?.note && selectedInvoice?.note.length">
            <span>{{ selectedInvoice.note }}</span>
            <span>ملاحظات:</span>
          </div>
          <div class="sign-wrapper">
            <div class="note-wrapper">
              <span>{{ format(new Date(selectedInvoice?.createdAt), 'dd-MM-yyyy HH:mm:ss') }}</span>
              :
              <span>التاريخ</span>
            </div>
            <div class="note-wrapper">
              <span>{{ selectedInvoice?.customerName || 'David John' }}</span>
              :
              <span>بواسطة</span>
            </div>
          </div>
          <div class="line">
          </div>
          <div class="footer">
            <p>شكراً لطبلكم خدماتنا</p>
            <p>www.website.com</p>
            <div class="social-wrapper">
              <img :src="`/images/home/twitter.svg`" alt="twitter" />
              <img :src="`/images/home/facebook.svg`" alt="facebook" />
              <p>social.account</p>
            </div>
          </div>
        </div>


        <div class="button-wrapper">
          <div class="flex justify-center items-center gap-2">
            <div class="bg-brandGreen rounded-lg flex px-6 py-3 justify-center items-center gap-2.5 cursor-pointer" @click="handleClickPrint">
              <span class="print-label">طباعة</span>
              <img :src="`/images/home/print_white.svg`" alt="printer" />
            </div>
            <div class="bg-white rounded-lg flex px-6 py-3 justify-center items-center gap-2.5 cursor-pointer"
            @click="handleClickDownload">
              <span class="download-label">PDF</span>
              <img :src="`/images/home/download_black.svg`" alt="printer" />
            </div>
          </div>
          <!-- <div class="flex justify-center items-center gap-2">
            <div class="flex px-6 py-3 justify-center items-center gap-2.5 cursor-pointer">
              <span class="print-label">إذهب إلى الفواتير</span>
              <img :src="`/images/home/invoice_white.svg" alt="printer" />
            </div>
          </div> -->
        </div>
      </div>
    </div>


  </OverlayLayer>
</template>

<style lang="scss" scoped>

.invoice-container {
    -ms-overflow-style: none;  /* Internet Explorer 10+ */
    scrollbar-width: none;  /* Firefox */
}
.invoice-container::-webkit-scrollbar { 
    display: none;  /* Safari and Chrome */
}
.invoice-wrapper {
  width: 485px;
  border-radius: 24px;
  background: var(--neutrals-text, #737F98);
  display: inline-flex;
  padding: 36px;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 24px;

  & .content-wrapper {
    display: flex;
    padding: 23px 21px;
    flex-direction: column;
    justify-content: center;
    align-items: flex-end;
    gap: 24px;

    .company-header {
      display: flex;
      padding: 29px 40px;
      align-items: center;
      gap: 24px;
      border-radius: 16px;
      background: var(--neutrals-sky-gray, #ECF1F4);

      .company-name {
        color: #000;
        text-align: right;
        font-size: 12px;
        font-family: Readex Pro;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
      }

      .company-logo {
        display: flex;
        width: 40px;
        height: 40px;
        justify-content: center;
        align-items: center;

        img {
          background-blend-mode: luminosity;
        }
      }
    }

    .invoice-title-wrapper {
      display: flex;
      flex-direction: column;
      align-self: stretch;
      color: #000;
      text-align: center;
      font-size: 15px;
      font-family: Readex Pro;
      font-style: normal;
      font-weight: 400;
      line-height: normal;
    }

    .line {
      width: 357px;
      height: 1px;
      background: #ECF1F4;
    }

    .customr-info {
      color: #000;
      text-align: center;
      font-size: 12px;
      font-family: Readex Pro;
      font-style: normal;
      font-weight: 400;
      line-height: normal;
    }

    .invoice-table-wrapper {
      width: 100%;
      border-radius: 16px;
      border: 1px solid var(--neutrals-sky-gray, #ECF1F4);

      table {
        border-style: none;

        th {
          color: var(--primary-dark-blue-03, #737F98);
          text-align: right;
          font-size: 10px;
          font-family: Readex Pro;
          font-style: normal;
          font-weight: 500;
          line-height: normal;
          text-align: right;
        }

        td {
          color: var(--neuturals-medium-dark, #484860);
          text-align: right;
          font-size: 10px;
          font-family: Readex Pro;
          font-style: normal;
          font-weight: 500;
          line-height: normal;
        }
      }
    }

    .note-wrapper {
      display: flex;
      align-items: flex-start;
      gap: 8px;
      color: #000;
      text-align: center;
      font-size: 12px;
      font-family: Readex Pro;
      font-style: normal;
      font-weight: 400;
      line-height: normal;
    }

    .sign-wrapper {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      gap: 8px;
    }

    .footer {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
      align-self: stretch;
      color: #000;
      text-align: center;
      font-size: 12px;
      font-family: Readex Pro;
      font-style: normal;
      font-weight: 400;
      line-height: normal;

      .social-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
      }
    }
  }

  & .button-wrapper {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;

    .print-label {
      color: #FFF;

      /* 14/Regular */
      font-size: 14px;
      font-family: Readex Pro;
      font-style: normal;
      font-weight: 400;
      line-height: normal;
    }

    .download-label {
      color: #484860;

      /* 14/Regular */
      font-size: 14px;
      font-family: Readex Pro;
      font-style: normal;
      font-weight: 400;
      line-height: normal;
    }
  }
}</style>