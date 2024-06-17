<!DOCTYPE html>
<html dir="rtl" lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Readex+Pro:wght@200;300;400;500;600;700&display=swap">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .invoice-wrapper {
            width: 485px;
            border-radius: 24px;
            background: #737f98;
            padding: 36px;
            min-width: 400px;
        }
        .content-wrapper {
            display: flex;
            padding: 23px 21px;
            flex-direction: column;
            justify-content: center;
            align-items: flex-end;
            gap: 24px;
        }

        .company-header {
            display: flex;
            padding: 29px 40px;
            align-items: center;
            justify-content: center;
            gap: 24px;
            border-radius: 16px;
            background: #ecf1f4;
            width: 100%;
            box-sizing: border-box;
        }

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
        }

        .company-logo > img {
            background-blend-mode: luminosity;
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
            background: #ecf1f4;
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
            border: 1px solid #ecf1f4;
        }

        table {
            border-style: none;
            text-indent: 0;
            border-color: inherit;
            border-collapse: collapse;
            width: 100%;
            box-sizing: border-box;
        }

        th {
            color:  #737f98;
            text-align: right;
            font-size: 10px;
            font-family: Readex Pro;
            font-style: normal;
            font-weight: 500;
            line-height: normal;
            text-align: right;
        }

        td {
            color: #484860;
            text-align: right;
            font-size: 10px;
            font-family: Readex Pro;
            font-style: normal;
            font-weight: 500;
            line-height: normal;
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
        }

        .social-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .flex {
            display: flex;
        }

        .gap-1 {
            gap: 4px;
        }

        .items-center {
            align-items: center;
        }

        .justify-end {
            justify-content: flex-end;
        }

        .bg-white {
            background-color: rgb(255 255 255 / 1);
        }

        .w-11\/12 {
            width: 80%;
        }

        @media (min-width: 768px) {
            .md\:w-3\/5 {
                width: 50%;
        }
        }

        @media (min-width: 1024px) {
            .lg\:w-2\/5 {
                width: 40%;
        }
        }

        @media (min-width: 1280px) {
            .xl\:w-4\/12 {
                width: 30%;
            }
        }

        tbody tr:nth-child(odd) {
            background-color: rgb(243 244 246 / 0.5);
        }

        td:not(:first-child) {
            border-left-width: 1px;
            border-top-width: 0px;
            border-right-width: 0px;
            border-bottom-width: 0px;
            border-color: rgb(243 244 246 / 1);
        }
        td {
            display: table-cell;
            border-bottom-width: 0px;
            padding: 0.75rem;
            text-align: right;
            vertical-align: middle;
        }

        th {
            padding: 0.75rem;
            text-align: right;
        }
  
    </style>
</head>

<body>
<div class="invoice-wrapper mt-36 shadow-lg w-11/12 md:w-3/5 lg:w-2/5 xl:w-4/12">
    <div class="content-wrapper bg-white">
      <div class="company-header">
      <div class="company-logo">
          <img src="/images/home/company_logo.svg" alt="logo" />
        </div>
        <div class="company-name">
          company name
          <br>
          sdfsdfsdfdsfdsf
          <p>+966 65489415@@</p>
        </div>
        
      </div>
      <div class="invoice-title-wrapper">
        فاتورة
        <br />
        INV-23232
      </div>
      <div class="line">
      </div>
      <div class="customer-info">
        <p class="flex gap-1 items-center justify-end">
          <span>أحمد أحمد</span>
          <span>الزبون</span>
        </p>
        <p><span>+966 45648153</span> : <span>رقم الهاتف</span></p>
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
            <tr>
              <td><span>ر.س</span> <span>23</span></td>
              <td>service title</td>
              <td>piece count</td>
              <td>ddd</td>
            </tr>
            <tr>
              <td>
                <span>ر.س</span> <span>23</span>
              </td>
              <td></td>
              <td></td>
              <td>المجموع</td>
            </tr>
            <tr>
              <td><span>ر.س</span> <span>10</span></td>
              <td></td>
              <td></td>
              <td>الخصم</td>
            </tr>
            <tr>
              <td><span>ر.س</span> <span>13</span></td>
              <td></td>
              <td></td>
              <td>المجموع الكلي</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="note-wrapper">
        <span>note text</span>
        <span>ملاحظات:</span>
      </div>
      <div class="sign-wrapper">
        <div class="note-wrapper">
          <span>11-08-2023 12:34:45:</span>
          <span>التاريخ</span>
        </div>
        <div class="note-wrapper">
          <span>David John</span>
          :
          <span>بواسطة</span>
        </div>
      </div>
      <div class="line"></div>
      <div class="footer">
        <p>شكراً لطبلكم خدماتنا</p>
        <p>www.website.com</p>
        <div class="social-wrapper">
          <p>social.account</p>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
