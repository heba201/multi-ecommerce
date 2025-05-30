<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Invoice #{{$order->id}}</title>

    <style>
        html,
        body {
            margin: 10px;
            padding: 10px;
            font-family: sans-serif;
        }
        h1,h2,h3,h4,h5,h6,p,span,label {
            font-family: sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0px !important;
        }
        table thead th {
            height: 28px;
            text-align: left;
            font-size: 16px;
            font-family: sans-serif;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 14px;
        }

        .heading {
            font-size: 24px;
            margin-top: 12px;
            margin-bottom: 12px;
            font-family: sans-serif;
        }
        .small-heading {
            font-size: 18px;
            font-family: sans-serif;
        }
        .total-heading {
            font-size: 18px;
            font-weight: 700;
            font-family: sans-serif;
        }
        .order-details tbody tr td:nth-child(1) {
            width: 20%;
        }
        .order-details tbody tr td:nth-child(3) {
            width: 20%;
        }

        .text-start {
            text-align: left;
        }
        .text-end {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .company-data span {
            margin-bottom: 4px;
            display: inline-block;
            font-family: sans-serif;
            font-size: 14px;
            font-weight: 400;
        }
        .no-border {
            border: 1px solid #fff !important;
        }
        .bg-blue {
            background-color: #414ab1;
            color: #fff;
        }
    </style>
</head>
<body>

    <table class="order-details">
        <thead>
            <tr>
                <th width="50%" colspan="2">
                   
                    <h2 class="text-start">{{App\Models\Setting::where('key', 'store_name')->first()->value}}</h2>
                </th>
                <th width="50%" colspan="2" class="text-end company-data">
                    <span>Invoice Id: #{{$order ->id}}</span> <br>
                    <span>Date: {{$order ->created_at->format('d-m-Y h:i A')}}</span> <br>
                    <span>Zip code : 560077</span> <br>
                    <span>Address: #555, Main road, shivaji nagar, Bangalore, India</span> <br>
                </th>
            </tr>
            <tr class="bg-blue">
                <th width="50%" colspan="2">Order Details</th>
                <th width="50%" colspan="2">User Details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Order Id:</td>
                <td>{{$order->id}}</td>

                <td>Full Name:</td>
                <td>{{$order ->	customer_name}}</td>
            </tr>
            <tr>
                <td>Tracking Id/No.:</td>
                <td>{{$order ->tracking_no}}</td>

                <td>Email Id:</td>
                <td>{{$order ->user()->first()->email}}</td>
            </tr>
            <tr>
                <td>Ordered Date:</td>
                <td>{{$order ->created_at->format('d-m-Y h:i A')}}</td>

                <td>Phone:</td>
                <td>{{$order ->	customer_phone}}</td>
            </tr>
            <tr>
                <td>Payment Mode:</td>
                <td>Cash on Delivery</td>

                <td>Address:</td>
                <td>asda asdad asdad adsasd</td>
            </tr>
            <tr>
                <td>Order Status:</td>
                <td>{{$order ->status}}</td>

                <td>Pin code:</td>
                <td>566999</td>
            </tr>
        </tbody>
    </table>

    <table>
        <thead>
            <tr>
                <th class="no-border text-start heading" colspan="5">
                    Order Items
                </th>
            </tr>
            <tr class="bg-blue">
                <th>ID</th>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
             $order_details= App\Models\Orderitem::where('order_id',$order->id)->get();
            ?>
            @if(isset($order_details))
            @foreach($order_details as $detail)
            <tr>
                <td width="10%">{{$detail->id}}</td>
                <td>
                {{$detail ->product()->first()->name}}
                </td>
                <td width="10%">{{$detail ->price}}</td>
                <td width="10%">{{$detail ->quantity}}</td>
                <td width="15%" class="fw-bold">{{$detail ->price * $detail ->quantity}}</td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>

    <br>
    <p class="text-center">
        Thank your for shopping with Funda of Web IT
    </p>

</body>
</html>