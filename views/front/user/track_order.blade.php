@extends('layouts.site')
@section('content')
<section class="section-box shop-template mt-30">
        <div class="container box-account-template">
          <h3>Hello {{auth()->user()->name}}</h3>
          <p class="font-md color-gray-500">{{tran('From your account dashboard. you can easily check & view your recent orders')}},<br class="d-none d-lg-block">{{tran('manage your shipping and billing addresses and edit your password and account details')}}.</p>
          <div class="box-tabs mb-100">
            <ul class="nav nav-tabs nav-tabs-account" role="tablist">

              <li><a class="active" href="#tab-order-tracking" data-bs-toggle="tab" role="tab" aria-controls="tab-order-tracking" aria-selected="true">Order Tracking</a></li>
            </ul>
            <div class="border-bottom mt-20 mb-40"></div>
            <div class="tab-content mt-30">
              <div class="tab-pane fade active show" id="tab-order-tracking" role="tabpanel" aria-labelledby="tab-order-tracking">
                <p class="font-md color-gray-600">{{tran('To track your order please enter your OrderID in the box below and press "Track" button')}}. {{tran('This was given to you on')}}<br class="d-none d-lg-block">{{tran('your receipt and in the confirmation email you should have received')}}.</p>
                <div class="row mt-30">
                  <div class="col-lg-6">
                    <div class="form-tracking">
                    <form class="" action="{{ route('orders.track') }}" method="GET" enctype="multipart/form-data">
                        <div class="d-flex">
                          <div class="form-group box-input">
                            <input class="form-control" type="text" name="order_code" placeholder="FDSFWRFAF13585">
                          </div>
                          <div class="form-group box-button">
                            <button class="btn btn-buy font-md-bold" type="submit">Tracking Now</button>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

                @isset($order)
                <div class="border-bottom mb-20 mt-20"></div>
                <h3 class="mb-10">Order Status: <span class="color-success">{{$order->delivery_status}}</span></h3>
                <h6 class="color-gray-500"></h6>
                <div class="table-responsive">
                  <div class="list-steps">
                    <div class="item-step">
                      <div class="rounded-step">
                        <div class="icon-step step-1 active"></div>
                        <h6 class="mb-5">{{tran('Order Placed')}}</h6>
                        <p class="font-md color-gray-500">{{date('d-m-Y', $order->date) }}</p>
                      </div>
                    </div>
                    <div class="item-step">
                      <div class="rounded-step">
                        <div class="icon-step step-2 active"></div>
                        <h6 class="mb-5"></h6>
                        <p class="font-md color-gray-500"></p>
                      </div>
                    </div>
                    <div class="item-step">
                      <div class="rounded-step">
                        <div class="icon-step step-3 active"></div>
                        <h6 class="mb-5"></h6>
                        <p class="font-md color-gray-500"></p>
                      </div>
                    </div>
                    <div class="item-step">
                      <div class="rounded-step">
                        <div class="icon-step step-4"></div>
                        <h6 class="mb-5"></h6>
                        <p class="font-md color-gray-500"></p>
                      </div>
                    </div>
                    <div class="item-step">
                      <div class="rounded-step">
                        <div class="icon-step step-5"></div>
                        <h6 class="mb-5"></h6>
                        <p class="font-md color-gray-500"></p>
                      </div>
                    </div>
                  </div>
                </div>


                <div class="bg-white border rounded-0 mt-5">
                    <div class="fs-15 fw-600 p-3">
                        {{ tran('Order Summary')}}
                    </div>
                    <div class="p-3">
                        <div class="row">
                            <div class="col-lg-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="w-50 fw-600">{{ tran('Order Code')}}:</td>
                                        <td>{{ $order->code }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ tran('Customer')}}:</td>
                                        <td>{{ json_decode($order->shipping_address)->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ tran('Email')}}:</td>
                                        @if ($order->user_id != null)
                                            <td>{{ $order->user->email }}</td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ tran('Shipping address')}}:</td>
                                        <td>{{ json_decode($order->shipping_address)->address }}, {{ json_decode($order->shipping_address)->city }}, {{ json_decode($order->shipping_address)->country }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-lg-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="w-50 fw-600">{{ tran('Order date')}}:</td>
                                        <td>{{ date('d-m-Y H:i A', $order->date) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ tran('Total order amount')}}:</td>
                                        <td>{{ single_price($order->orderDetails->sum('price') + $order->orderDetails->sum('tax')) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ tran('Shipping method')}}:</td>
                                        <td>{{ tran('Flat shipping rate')}}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ tran('Payment method')}}:</td>
                                        <td>{{ tran(ucfirst(str_replace('_', ' ', $order->payment_type))) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ tran('Delivery Status')}}:</td>
                                        <td>{{ tran(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}</td>
                                    </tr>
                                    @if ($order->tracking_code)
                                        <tr>
                                            <td class="w-50 fw-600">{{ tran('Tracking code')}}:</td>
                                            <td>{{ $order->tracking_code }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
              
              
              </div>
              
            </div>
          </div>

      
                    @php
                        $status = $order->delivery_status;
                    @endphp
                    <div class="bg-white border rounded-0 mt-4" style="margin-bottom:10px !important">
                        
                    @if($order->orderDetails->count() > 0)
                        <div class="p-3">
                            <table class="table">
                                <thead>
                                    <tr style="border-bottom:1px solid #ededf2">
                                        <th class="border-0">{{ tran('Product Name')}}</th>
                                        <th class="border-0">{{ tran('Quantity')}}</th>
                                        <th class="border-0">{{ tran('Shipped By')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($order->orderDetails as $key => $orderDetail)
                                    <tr>
                                    <td>{{ $orderDetail->product->name }} @if($orderDetail->variation !="")({{ $orderDetail->variation }})@endif</td>
                                        <td>{{ $orderDetail->quantity }}</td>
                                        <td>{{ $orderDetail->product->user->name }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
               

            @endisset

        </div>
      </section>
    @endsection