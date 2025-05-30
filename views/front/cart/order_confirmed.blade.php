@extends('layouts.site')
@section('content')
<style>
.text-soft-dark{
    color:black !important;
}
</style>
    <!-- Steps -->
    <section class="pt-5 mb-0">
        <div class="container">
        </div>
    </section>

    <!-- Order Confirmation -->
    <section class="py-4">
        <div class="container text-left">
            <div class="row">

            <div class="col-lg-12">
            <div class="box-border">
            <div class="box-payment">
                <div class="col  done" style="padding:10px;">
                
                            <div class="text-center border border-bottom-6px p-2 text-primary">
                          
                            <i class="fa-solid fa-map fa-3x"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ tran('1. Shipping info') }}
                              
                              </h3>
                            </div>
                        </div>
                        <div class="col done"  style="padding:10px;">
                
                <div class="text-center border border-bottom-6px p-2 text-primary">
                
                <i class="fa-solid fa-truck-fast fa-3x"></i>
                    <h3 class="fs-14 fw-600 d-none d-lg-block">{{ tran('2. Delivery Information') }}
                  
                  </h3>
                </div>
            </div>
                 
            <div class="col done"  style="padding:10px;">
                
                <div class="text-center border border-bottom-6px p-2 text-primary">
                <i class="fa-solid fa-credit-card fa-3x"></i>
                    <h3 class="fs-14 fw-600 d-none d-lg-block">{{ tran('3. Payment') }}
                  
                  </h3>
                </div>
            </div>

            <div class="col done"  style="padding:10px;">
                
                <div class="text-center border border-bottom-6px p-2 text-primary">
                <i class="fa-solid fa-circle-check fa-3x"></i>
                    <h3 class="fs-14 fw-600 d-none d-lg-block">{{ tran('3. Confirmation') }}
                  
                  </h3>
                </div>
            </div>

                </div>
                </div>
                </div>
                
                <div class="col-xl-8 mx-auto">
                    @php
                        $first_order = $combined_order->orders->first()
                    @endphp
                    <!-- Order Confirmation Text-->
                    <div class="text-center py-4 mb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" class=" mb-3">
                            <g id="Group_23983" data-name="Group 23983" transform="translate(-978 -481)">
                              <circle id="Ellipse_44" data-name="Ellipse 44" cx="18" cy="18" r="18" transform="translate(978 481)" fill="#85b567"/>
                              <g id="Group_23982" data-name="Group 23982" transform="translate(32.439 8.975)">
                                <rect id="Rectangle_18135" data-name="Rectangle 18135" width="11" height="3" rx="1.5" transform="translate(955.43 487.707) rotate(45)" fill="#fff"/>
                                <rect id="Rectangle_18136" data-name="Rectangle 18136" width="3" height="18" rx="1.5" transform="translate(971.692 482.757) rotate(45)" fill="#fff"/>
                              </g>
                            </g>
                        </svg>
                        <h1 class="mb-2 fs-28 fw-500 text-success">{{ tran('Thank You for Your Order!')}}</h1>
                        <p class="fs-13 text-soft-dark">{{  tran('A copy or your order summary has been sent to') }} <strong>{{ json_decode($first_order->shipping_address)->email }}</strong></p>
                    </div>
                    <!-- Order Summary -->
                    <div class="mb-4 bg-white p-4 border">
                        <h5 class="fw-600 mb-3 fs-16 text-soft-dark pb-2 border-bottom">{{ tran('Order Summary')}}</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table fs-14 text-soft-dark">
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 pl-0 py-2">{{ tran('Order date')}}:</td>
                                        <td class="border-top-0 py-2">{{ date('d-m-Y H:i A', $first_order->date) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 pl-0 py-2">{{ tran('Name')}}:</td>
                                        <td class="border-top-0 py-2">{{ json_decode($first_order->shipping_address)->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 pl-0 py-2">{{ tran('Email')}}:</td>
                                        <td class="border-top-0 py-2">{{ json_decode($first_order->shipping_address)->email }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 pl-0 py-2">{{ tran('Shipping address')}}:</td>
                                        <td class="border-top-0 py-2">{{ json_decode($first_order->shipping_address)->address }}, {{ json_decode($first_order->shipping_address)->city }}, {{ json_decode($first_order->shipping_address)->country }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 py-2">{{ tran('Order status')}}:</td>
                                        <td class="border-top-0 pr-0 py-2">{{ tran(ucfirst(str_replace('_', ' ', $first_order->delivery_status))) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 py-2">{{ tran('Total order amount')}}:</td>
                                        <td class="border-top-0 pr-0 py-2">{{ single_price($combined_order->grand_total) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 py-2">{{ tran('Shipping')}}:</td>
                                        <td class="border-top-0 pr-0 py-2">{{ tran('Flat shipping rate')}}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 py-2">{{ tran('Payment method')}}:</td>
                                        <td class="border-top-0 pr-0 py-2">{{ tran(ucfirst(str_replace('_', ' ', $first_order->payment_type))) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Info -->
                    @foreach ($combined_order->orders as $order)
                        <div class="card shadow-none border rounded-0">
                            <div class="card-body">
                                <!-- Order Code -->
                                <div class="text-center py-1 mb-4">
                                    <h2 class="h5 fs-20">{{ tran('Order Code:')}} <span class="fw-700 text-primary">{{ $order->code }}</span></h2>
                                </div>
                                <!-- Order Details -->
                                <div>
                                    <h5 class="fw-600 text-soft-dark mb-3 fs-16 pb-2">{{ tran('Order Details')}}</h5>
                                    <!-- Product Details -->
                                    <div>
                                        <table class="table table-responsive-md text-soft-dark fs-14">
                                            <thead>
                                                <tr>
                                                    <th class="opacity-60 border-top-0 pl-0">#</th>
                                                    <th class="opacity-60 border-top-0" width="30%">{{ tran('Product')}}</th>
                                                    <th class="opacity-60 border-top-0">{{ tran('Variation')}}</th>
                                                    <th class="opacity-60 border-top-0">{{ tran('Quantity')}}</th>
                                                    <th class="opacity-60 border-top-0">{{ tran('Delivery Type')}}</th>
                                                    <th class="text-right opacity-60 border-top-0 pr-0">{{ tran('Price')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($order->orderDetails as $key => $orderDetail)
                                                    <tr>
                                                        <td class="border-top-0 border-bottom pl-0">{{ $key+1 }}</td>
                                                        <td class="border-top-0 border-bottom">
                                                            @if ($orderDetail->product != null)
                                                                <a href="{{ route('product.details', $orderDetail->product->slug) }}" target="_blank" class="text-reset">
                                                                    {{ $orderDetail->product->name }}
                                                                    @php
                                                                        if($orderDetail->combo_id != null) {
                                                                            $combo = \App\ComboProduct::findOrFail($orderDetail->combo_id);

                                                                            echo '('.$combo->combo_title.')';
                                                                        }
                                                                    @endphp
                                                                </a>
                                                            @else
                                                                <strong>{{  tran('Product Unavailable') }}</strong>
                                                            @endif
                                                        </td>
                                                        <td class="border-top-0 border-bottom">
                                                            {{ $orderDetail->variation }}
                                                        </td>
                                                        <td class="border-top-0 border-bottom">
                                                            {{ $orderDetail->quantity }}
                                                        </td>
                                                        <td class="border-top-0 border-bottom">
                                                            @if ($order->shipping_type != null && $order->shipping_type == 'home_delivery')
                                                                {{  tran('Home Delivery') }}
                                                            @elseif ($order->shipping_type != null && $order->shipping_type == 'carrier')
                                                                {{  tran('Carrier') }}
                                                            @elseif ($order->shipping_type == 'pickup_point')
                                                                @if ($order->pickup_point != null)
                                                                    {{ $order->pickup_point->name }} ({{ tran('Pickip Point') }})
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td class="border-top-0 border-bottom pr-0 text-right">{{ single_price($orderDetail->price) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Order Amounts -->
                                    <div class="row">
                                        <div class="col-xl-5 col-md-6 ml-auto mr-0">
                                            <table class="table ">
                                                <tbody>
                                                    <!-- Subtotal -->
                                                    <tr>
                                                        <th class="border-top-0 py-2">{{ tran('Subtotal')}}</th>
                                                        <td class="text-right border-top-0 pr-0 py-2">
                                                            <span class="fw-600">{{ single_price($order->orderDetails->sum('price')) }}</span>
                                                        </td>
                                                    </tr>
                                                    <!-- Shipping -->
                                                    <tr>
                                                        <th class="border-top-0 py-2">{{ tran('Shipping')}}</th>
                                                        <td class="text-right border-top-0 pr-0 py-2">
                                                            <span>{{ single_price($order->orderDetails->sum('shipping_cost')) }}</span>
                                                        </td>
                                                    </tr>
                                                    <!-- Tax -->
                                                    <tr>
                                                        <th class="border-top-0 py-2">{{ tran('Tax')}}</th>
                                                        <td class="text-right border-top-0 pr-0 py-2">
                                                            <span>{{ single_price($order->orderDetails->sum('tax')) }}</span>
                                                        </td>
                                                    </tr>
                                                    <!-- Coupon Discount -->
                                                    <tr>
                                                        <th class="border-top-0 py-2">{{ tran('Coupon Discount')}}</th>
                                                        <td class="text-right border-top-0 pr-0 py-2">
                                                            <span>{{ single_price($order->coupon_discount) }}</span>
                                                        </td>
                                                    </tr>
                                                    <!-- Total -->
                                                    <tr>
                                                        <th class="py-2"><span class="fw-600">{{ tran('Total')}}</span></th>
                                                        <td class="text-right pr-0">
                                                            <strong><span>{{ single_price($order->grand_total) }}</span></strong>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
