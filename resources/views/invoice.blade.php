<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style type="text/css">

        body {
            font-family: 'readexpro', sans-serif;
            max-width: 900px;
            margin: auto;
        }
        .invoice-wrapper {
            border-radius: 24px;
            padding: 36px;
            min-width: 400px;
            margin: auto;
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
            padding: 29px 40px;
            border-radius: 16px;
            background: #ecf1f4;
            width: 100%;
            box-sizing: border-box;
        }

        .company-name {
            width: 70%;
            float: left;
            color: #000;
            text-align: right;
            font-size: 16px;
            font-family: 'readexpro', sans-serif;
            font-style: normal;
            font-weight: 400;
            line-height: normal;
        }

        .company-logo {
            margin-left: 20px;
            width: 20%;
            float: left;
            width: 40px;
            min-width: 40px;
            height: 40px;
            min-height: 40px;
        }

        .company-logo > svg {
            background-blend-mode: luminosity;
        }

        .invoice-title-wrapper {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            align-self: stretch;
            color: #000;
            text-align: center;
            font-size: 18px;
            font-family: 'readexpro', sans-serif;
            font-style: normal;
            font-weight: 400;
            line-height: normal;
        }

        .line {
            width: 100%;
            height: 1px;
            background: #ecf1f4;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .customer-info {
            color: #000;
            text-align: right;
            font-size: 16px;
            font-family: 'readexpro', sans-serif;
            font-style: normal;
            font-weight: 400;
            line-height: normal;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .invoice-table-wrapper {
            width: 100%;
            border-radius: 16px;
            border: 1px solid #ecf1f4;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        

        .note-wrapper {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            color: #000;
            text-align: right;
            font-size: 16px;
            font-family: 'readexpro', sans-serif;
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
            margin-top: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            align-self: stretch;
            color: #000;
            text-align: center;
            font-size: 16px;
            font-family: 'readexpro', sans-serif;
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
            background-color: rgb(255, 255, 255, 1);
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
            color: #737f98;
            text-align: right;
            font-size: 16px;
            font-family: 'readexpro', sans-serif;
            font-style: normal;
            font-weight: 500;
            line-height: normal;
            text-align: right;
            padding: 0.75rem;
            text-align: right;
        }

        tbody tr:nth-child(odd) {
            background-color: rgb(243, 244, 246, 0.5);
        }

        td:not(:first-child) {
            border-left-width: 1px;
            border-top-width: 0px;
            border-right-width: 0px;
            border-bottom-width: 0px;
            border-color: rgb(243, 244, 246, 1);
        }

        td {
            display: table-cell;
            border-bottom-width: 0px;
            padding: 0.75rem;
            text-align: right;
            vertical-align: middle;
            color: #484860;
            text-align: right;
            font-size: 16px;
            font-style: normal;
            font-weight: 500;
            line-height: normal;
        }
    </style>
</head>

<body>
    <div class="invoice-wrapper">
        <div class="content-wrapper bg-white">
            <div class="company-header flex items-center">
                <div class="company-name">
                    اسم الشركة              
                    <br>
                    المملكة العربية السعودية - جدة - شارع
                    <p>+966 65489415</p>
                </div>
                <div class="company-logo">
                  <!-- <img src="/images/home/company_logo.svg" alt="logo" /> -->
                    <svg width="40" height="41" viewBox="0 0 40 41" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <rect y="0.5" width="40" height="40" fill="url(#pattern0)" style="mix-blend-mode:luminosity"/>
                        <defs>
                            <pattern id="pattern0" patternContentUnits="objectBoundingBox" width="1" height="1">
                                <use xlink:href="#image0_368_708" transform="scale(0.00625)"/>
                            </pattern>
                            <image id="image0_368_708" width="160" height="160" xlink:href="data:image/png;base64,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"/>
                        </defs>
                    </svg>
                </div>
            </div>
            <div class="invoice-title-wrapper">
                فاتورة
                <br />
                INV-{{$invoice[0]->invoiceData->daily_id}}
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
                    @php $total = 0; $alltotal = 0; $allDiscount = 0; $note = ''; @endphp
                    @foreach ($invoice as $piece)
                        @foreach ($piece->invoiceservicelist as $service)
                            @php 
                                if($service->total_price != null)
                                {
                                    $total = $service->total_price; 
                                    $service_price = $service->dtl; 
                                }
                                else
                                {
                                    $service_price = $service->service_piece->price.'ريال'; 
                                    $total = $total + ($service->service_piece->price * $service->number_of_piece); 
                                }
                                @endphp
                            <tr>
                                
                                <td><span>ر.س</span><span>{{ $total }}</span></td>
                                <td>{{ $service->service_piece->serviceinfo->services_title }}</td>
                                <td>{{ $service->number_of_piece }}</td>
                                <!-- <td>{{ $piece->piece_title }}</td> -->
                                <td>{{ $service->service_piece->pieceinfo->piece_title }}</td>
                                @php $alltotal = $alltotal + $total; $total = 0; @endphp
                            </tr>
                        @endforeach
                    @endforeach
                        <!-- Total price -->
                        <tr>
                            <td>
                                <span>ر.س</span> <span>{{$alltotal}}</span>
                            </td>
                            <td></td>
                            <td></td>
                            <td>المجموع</td>
                        </tr>
                        <!-- Total Discount -->
                        <tr>
                            <td><span>ر.س</span> <span>{{$allDiscount}}</span></td>
                            <td></td>
                            <td></td>
                            <td>الخصم</td>
                        </tr>
                        <!-- Summed price -->
                        <tr>
                            <td><span>ر.س</span> <span>{{$alltotal - $allDiscount}}</span></td>
                            <td></td>
                            <td></td>
                            <td>المجموع الكلي</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="line"></div>
            @if(strlen($note) > 0)
            <div class="note-wrapper">
                <span>{{$note}}</span>
                <span>ملاحظات:</span>
            </div>
            @endif
            <div class="line"></div>
            <div class="sign-wrapper">
                <div class="note-wrapper">
                    <span>{{date('d-m-Y h:m:s:', strtotime($invoice[0]->invoiceData->created_at));}}</span>
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
                    <svg width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M6.99996 1.6665C3.78346 1.6665 1.16663 4.28334 1.16663 7.49984C1.16663 10.4183 3.32321 12.8359 6.12496 13.2603V8.95817H4.95829C4.79729 8.95817 4.66663 8.8278 4.66663 8.6665V7.7915C4.66663 7.63021 4.79729 7.49984 4.95829 7.49984H6.12496V6.4405C6.12496 5.0548 6.83283 4.2915 8.11821 4.2915C8.63913 4.2915 9.05008 4.32767 9.06729 4.32913C9.21779 4.34255 9.33329 4.46855 9.33329 4.61963V5.604C9.33329 5.7653 9.20263 5.89567 9.04163 5.89567H8.45829C8.13658 5.89567 7.87496 6.1573 7.87496 6.479V7.49984H9.04163C9.12533 7.49984 9.20496 7.53571 9.26038 7.59842C9.31579 7.66142 9.34146 7.74484 9.33125 7.82767L9.22188 8.70267C9.2035 8.8485 9.07925 8.95817 8.93225 8.95817H7.87496V13.2603C10.6767 12.8359 12.8333 10.4183 12.8333 7.49984C12.8333 4.28334 10.2165 1.6665 6.99996 1.6665Z"
                            fill="black" />
                    </svg>

                    <svg width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M13.0431 3.50563C12.957 3.41667 12.8246 3.39129 12.7117 3.44321L12.6639 3.46508C12.6234 3.48375 12.5828 3.50242 12.542 3.52079C12.6607 3.3315 12.7549 3.12908 12.82 2.91967C12.8561 2.80417 12.8167 2.67787 12.7211 2.60321C12.6254 2.52854 12.4936 2.52067 12.39 2.58396C12.0572 2.78608 11.7376 2.93163 11.4071 3.03313C10.9084 2.54925 10.2291 2.25 9.47921 2.25C7.94883 2.25 6.70837 3.49046 6.70837 5.02083C6.70837 5.02229 6.70837 5.08004 6.70837 5.16667L6.417 5.14333C3.58113 4.80792 2.77379 2.90042 2.74025 2.81817C2.686 2.68108 2.56642 2.58104 2.42204 2.551C2.27796 2.52038 2.12833 2.56558 2.02392 2.66971C1.96617 2.72775 1.45838 3.265 1.45838 4.29167C1.45838 5.02317 1.78446 5.61642 2.2065 6.07783C2.00992 5.95796 1.89529 5.86083 1.89237 5.85821C1.75792 5.74096 1.56542 5.71646 1.40558 5.79754C1.24633 5.87892 1.15242 6.04896 1.16846 6.22717C1.174 6.28871 1.29825 7.45596 2.64663 8.33737L2.40075 8.382C2.25171 8.40912 2.12717 8.5115 2.07175 8.65267C2.01663 8.79412 2.03821 8.95367 2.12921 9.075C2.15983 9.11612 2.72946 9.85667 3.96642 10.2825C3.30579 10.5027 2.39813 10.7083 1.31254 10.7083C1.14104 10.7083 0.985 10.8087 0.914125 10.965C0.842958 11.1213 0.870083 11.3048 0.98325 11.4337C1.03021 11.4877 2.16858 12.75 5.10421 12.75C9.97883 12.75 12.25 8.22479 12.25 5.16667V5.02083C12.25 4.97767 12.2454 4.93537 12.2436 4.89279C12.8538 4.30304 13.0833 3.86058 13.0944 3.83871C13.1495 3.72787 13.1291 3.59429 13.0431 3.50563Z"
                            fill="black" />
                    </svg>
                    <p>social.account</p>
               
                </div>
            </div>
        </div>
    </div>
</body>

</html>