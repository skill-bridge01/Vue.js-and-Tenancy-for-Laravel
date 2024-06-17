<!DOCTYPE html>
<html dir="rtl" lang="ar">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .header {
            background-color: #87CEEB;
            color: #333;
            padding: 20px;
            text-align: center;
        }
        
        .invoice-table {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
        }
        
        .invoice-table th,
        .invoice-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        
        .invoice-table th {
            background-color: #87CEEB;
            color: #333;
            text-align: center;
        }
        
        .invoice-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        .invoice-table tr:hover {
            background-color: #ddd;
        }
        
        .invoice-total {
            background-color: #87CEEB;
            color: #333;
            text-align: right;
            padding: 8px;
        }
        
        .invoice-total td:last-child {
            font-weight: bold;
        }
        .title {
            color: red;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1 class="title">عنوان الفاتورة</h1>
    </div>
    <div class="invoice-wrapper">
        <div class="content-wrapper bg-white">
            <div class="company-name">
                Company Name
                <br>
                
            </div>
        </div>
    </div>
    <table class="invoice-table">
        <tr class="heading">
            <th>طريقة الدفع</th>
            <th>رقم الشيك</th>
        </tr>
        <tr class="details">
            <td>شيك</td>
            <td>1000</td>
        </tr>
        <tr class="heading">
            <th>القطعة</th>
            <th>عدد القطع</th>
            <th>الخدمات</th>
            <th>سعر الخدمات</th>
        </tr>
        @php $total = 0; $alltotal = 0; @endphp
        @foreach ($invoice as $piece)
        <tr class="item">
            <td>{{ $piece->piece_title }}</td>
            <td>{{ $piece->invoiceservicelist[0]->number_of_piece }}</td>
            <td>
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
                {{ $service->service_piece->serviceinfo->services_title }}
                {{ $service_price }}
                @endforeach
            </td>
            <td>{{ $total }}</td>
            @php $alltotal = $alltotal + $total; $total = 0; @endphp
        </tr>
        @endforeach
        <tr class="total">
            <td></td>
            <td colspan="3" class="invoice-total">اجمالي الفاتورة: {{ $alltotal }}</td>
        </tr>
    </table>
</body>

</html>
