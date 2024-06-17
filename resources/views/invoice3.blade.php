<!DOCTYPE html>
<html dir="rtl" lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
}
</style>

</head>

<body>
            <div class="content-wrapper bg-white">
              <div class="company-header">
                <div class="company-name">
                  اسم الشركة
                  <br>
                  المملكة العربية السعودية - جدة - شارع
                  <p>+966 65489415</p>
                </div>
                <div class="company-logo">
                  <img src="/images/home/company_logo.svg" alt="logo" />
                </div>
              </div>
              <div class="invoice-title-wrapper">
                فاتورة
                <br />
                INV-<span v-text="selectedInvoice.id"></span>
              </div>
              <div class="line">
              </div>
              <div class="customer-info">
                <p class="flex gap-1 items-center justify-end">
                  <span v-text="selectedInvoice?.customer?.name || 'أحمد أحمد'"></span>
                  <span>:الزبون</span>
                </p>
                <p><span v-text="selectedInvoice?.customer?.mobile || '+966 45648153'"></span>
                  : <span>رقم الهاتف</span>
                </p>
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
                      <td><span>ر.س</span> <span v-text="service.price"></span></td>
                      <td><span v-text="service.serviceinfo.services_title"></span></td>
                      <td><span v-text="service.pivot.number_of_piece"></span></td>
                      <td><span></span></td>
                    </tr>
                    <tr v-if="selectedInvoice.totalPrice">
                      <td>
                        <span>ر.س</span> <span v-text="selectedInvoice.totalPrice"></span>
                      </td>
                      <td></td>
                      <td></td>
                      <td>المجموع</td>
                    </tr>
                    <tr v-if="selectedInvoice.totalDiscount">
                      <td><span>ر.س</span> <span v-text="selectedInvoice.totalDiscount"></span></td>
                      <td></td>
                      <td></td>
                      <td>الخصم</td>
                    </tr>
                    <tr v-if="selectedInvoice.totalPrice && selectedInvoice.totalDiscount">
                      <td><span>ر.س</span> <span v-text="selectedInvoice.totalPrice - selectedInvoice.totalDiscount"></span></td>
                      <td></td>
                      <td></td>
                      <td>المجموع الكلي</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="note-wrapper" v-if="note && note.length > 0">
                <span v-text="note"></span>
                <span>ملاحظات:</span>
              </div>
              <div class="sign-wrapper">
                <div class="note-wrapper">
                  <span v-text="format(new Date(selectedInvoice?.createdAt), 'dd-MM-yyyy HH:mm:ss')"></span>
                  :
                  <span>التاريخ</span>
                </div>
                <div class="note-wrapper">
                  <span v-text="selectedInvoice?.customer.name || 'David John'"></span>
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
                  <img src="/images/home/twitter.svg" alt="twitter" />
                  <img src="/images/home/facebook.svg" alt="facebook" />
                  <p>social.account</p>
                </div>
              </div>
            </div>
    
    
          
          </div>
        </div>
      </div>
    </body>
</html>