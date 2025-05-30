@extends('seller.layouts.app')

@section('content')
<div class="page-header">
            <h3 class="page-title">
            {{tran('E-commerce')}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{tran('E-commerce')}}</a></li>
                <li class="breadcrumb-item active" aria-current="page"> {{tran('Orders')}}</li>
                </ol>
            </nav>
            </h3>
            
          </div>
    <div class="card">
        <form id="sort_orders" action="" method="GET">
          <div class="card-header row gutters-5">
            <div class="col text-center text-md-left">
              <h5 class="mb-md-0 h6">{{ tran('Orders') }}</h5>
            </div>
              <div class="col-md-3 ml-auto">
                  <select class="form-control aiz-selectpicker" data-placeholder="{{ tran('Filter by Payment Status')}}" name="payment_status" onchange="sort_orders()">
                      <option value="">{{ tran('Filter by Payment Status')}}</option>
                      <option value="paid" @isset($payment_status) @if($payment_status == 'paid') selected @endif @endisset>{{ tran('Paid')}}</option>
                      <option value="unpaid" @isset($payment_status) @if($payment_status == 'unpaid') selected @endif @endisset>{{ tran('Unpaid')}}</option>
                  </select>
              </div>

              <div class="col-md-3 ml-auto">
                <select class="form-control aiz-selectpicker" data-placeholder="{{ tran('Filter by Payment Status')}}" name="delivery_status" onchange="sort_orders()">
                    <option value="">{{ tran('Filter by Deliver Status')}}</option>
                    <option value="pending" @isset($delivery_status) @if($delivery_status == 'pending') selected @endif @endisset>{{ tran('Pending')}}</option>
                    <option value="confirmed" @isset($delivery_status) @if($delivery_status == 'confirmed') selected @endif @endisset>{{ tran('Confirmed')}}</option>
                    <option value="on_delivery" @isset($delivery_status) @if($delivery_status == 'on_delivery') selected @endif @endisset>{{ tran('On delivery')}}</option>
                    <option value="delivered" @isset($delivery_status) @if($delivery_status == 'delivered') selected @endif @endisset>{{ tran('Delivered')}}</option>
                </select>
              </div>
              <div class="col-md-3">
                <div class="from-group mb-0">
                    <input type="text" class="form-control" id="search" name="search" @isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ tran('Type Order code & hit Enter') }}">
                </div>
              </div>
          </div>
        </form>

        
            <div class="card-body p-3">
                <table class="table  mb-0" id="order-listing">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ tran('Order Code')}}</th>
                            <th data-breakpoints="lg">{{ tran('Num. of Products')}}</th>
                            <th data-breakpoints="lg">{{ tran('Customer')}}</th>
                            <th data-breakpoints="md">{{ tran('Amount')}}</th>
                            <th data-breakpoints="lg">{{ tran('Delivery Status')}}</th>
                            <th>{{ tran('Payment Status')}}</th>
                            <th class="text-right">{{ tran('Options')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $key => $order_id)
                            @php
                                $order = \App\Models\Order::find($order_id->id);
                            @endphp
                            @if($order != null)
                                <tr>
                                    <td>
                                        {{ $key+1 }}
                                    </td>
                                    <td>
                                        <a href="#{{ $order->code }}" onclick="show_order_details({{ $order->id }})">{{ $order->code }}</a>
                                    </td>
                                    <td>
                                        {{ count($order->orderDetails->where('seller_id', Auth::user()->id)) }}
                                    </td>
                                    <td>
                                        @if ($order->user_id != null)
                                            {{ optional($order->user)->name }}
                                        @else
                                            {{ tran('Guest') }} ({{ $order->guest_id }})
                                        @endif
                                    </td>
                                    <td>
                                        {{ single_price($order->grand_total) }}
                                    </td>
                                    <td>
                                        @php
                                            $status = $order->delivery_status;
                                        @endphp
                                        {{ tran(ucfirst(str_replace('_', ' ', $status))) }}
                                    </td>
                                    <td>
                                        @if ($order->payment_status == 'paid')
                                            <span class="badge badge-inline badge-success">{{ tran('Paid')}}</span>
                                        @else
                                            <span class="badge badge-inline badge-danger">{{ tran('Unpaid')}}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('orders.show', encrypt($order->id)) }}" class="btn btn-light btn-sm" style="padding:10px" title="{{ tran('Order Details') }}">
                                        <i class="fa fa-eye text-primary"></i>
                                        </a>
                                        <a href="{{ route('seller.invoice.download', $order->id) }}" class="btn btn-light btn-sm" style="padding:10px" title="{{ tran('Download Invoice') }}">
                                        <i class="fa-solid fa-download"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                
            </div>
       
    </div>

@endsection

@push('js')
    <script type="text/javascript">
        function sort_orders(el){
            $('#sort_orders').submit();
        }
    </script>
@endpush
