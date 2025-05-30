@extends('seller.layouts.app')

@section('content')
<style>
    .card .col-md-4 div:last-child hr {
    display: none;
}
</style>

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
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">{{ tran('Order Details') }}</h1>
        </div>

        <div class="card-body">
            <div class="row gutters-5">
                <div class="col text-md-left text-center">
                </div>
                @php
                    $delivery_status = $order->delivery_status;
                    $payment_status = $order->orderDetails->where('seller_id', Auth::user()->id)->first()->payment_status;
                @endphp
                
                @if (get_setting('product_manage_by_admin') == 0)
                    <div class="col-md-3 ml-auto">
                        <label for="update_payment_status">{{ tran('Payment Status') }}</label>
                        @if ( $order->payment_type == 'cash_on_delivery'  && $payment_status == 'unpaid')
                            <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                                id="update_payment_status">
                                <option value="unpaid" @if ($payment_status == 'unpaid') selected @endif>
                                    {{ tran('Unpaid') }}</option>
                                <option value="paid" @if ($payment_status == 'paid') selected @endif>
                                    {{ tran('Paid') }}</option>
                            </select>
                        @else
                            <input type="text" class="form-control" value="{{ tran($payment_status) }}" disabled>
                        @endif
                    </div>
                    <div class="col-md-3 ml-auto">
                        <label for="update_delivery_status">{{ tran('Delivery Status') }}</label>
                        @if ($delivery_status != 'delivered' && $delivery_status != 'cancelled')
                            <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                                id="update_delivery_status">
                                <option value="pending" @if ($delivery_status == 'pending') selected @endif>
                                    {{ tran('Pending') }}</option>
                                <option value="confirmed" @if ($delivery_status == 'confirmed') selected @endif>
                                    {{ tran('Confirmed') }}</option>
                                <option value="picked_up" @if ($delivery_status == 'picked_up') selected @endif>
                                    {{ tran('Picked Up') }}</option>
                                <option value="on_the_way" @if ($delivery_status == 'on_the_way') selected @endif>
                                    {{ tran('On The Way') }}</option>
                                <option value="delivered" @if ($delivery_status == 'delivered') selected @endif>
                                    {{ tran('Delivered') }}</option>
                                <option value="cancelled" @if ($delivery_status == 'cancelled') selected @endif>
                                    {{ tran('Cancel') }}</option>
                            </select>
                        @else
                            <input type="text" class="form-control" value="{{ tran(ucfirst(str_replace('_', ' ', $delivery_status))) }}" disabled>
                        @endif
                    </div>
                @endif
            </div>
            <div class="row gutters-5 mt-2">
                <div class="col text-md-left text-center">
                   
                    @if(json_decode($order->shipping_address))
                        <address>
                            <strong class="text-main">
                                {{ json_decode($order->shipping_address)->name }}
                            </strong><br>
                            {{ json_decode($order->shipping_address)->email }}<br>
                            {{ json_decode($order->shipping_address)->phone }}<br>
                            {{ json_decode($order->shipping_address)->address }}, {{ json_decode($order->shipping_address)->city }}, @if(isset(json_decode($order->shipping_address)->state)) {{ json_decode($order->shipping_address)->state }} - @endif {{ json_decode($order->shipping_address)->postal_code }}<br>
                            {{ json_decode($order->shipping_address)->country }}
                        </address>
                    @else
                        <address>
                            <strong class="text-main">
                                {{ $order->user->name }}
                            </strong><br>
                            {{ $order->user->email }}<br>
                            {{ $order->user->phone }}<br>
                        </address>
                    @endif
                    @if ($order->manual_payment && is_array(json_decode($order->manual_payment_data, true)))
                        <br>
                        <strong class="text-main">{{ tran('Payment Information') }}</strong><br>
                        {{ tran('Name') }}: {{ json_decode($order->manual_payment_data)->name }},
                        {{ tran('Amount') }}:
                        {{ single_price(json_decode($order->manual_payment_data)->amount) }},
                        {{ tran('TRX ID') }}: {{ json_decode($order->manual_payment_data)->trx_id }}
                        <br>
                        <a href="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}"
                            target="_blank"><img
                                src="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" alt=""
                                height="100"></a>
                    @endif
                </div>
                <div class="col-md-4 ml-auto">
                    <table>
                        <tbody>
                            <tr>
                                <td class="text-main text-bold">{{ tran('Order #') }}</td>
                                <td class="text-info text-bold text-right">{{ $order->code }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ tran('Order Status') }}</td>
                                <td class="text-right">
                                    @if ($delivery_status == 'delivered')
                                        <span
                                            class="badge badge-inline badge-success">{{ tran(ucfirst(str_replace('_', ' ', $delivery_status))) }}</span>
                                    @else
                                        <span
                                            class="badge badge-inline badge-info">{{ tran(ucfirst(str_replace('_', ' ', $delivery_status))) }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ tran('Order Date') }}</td>
                                <td class="text-right">{{ date('d-m-Y h:i A', $order->date) }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ tran('Total amount') }}</td>
                                <td class="text-right">
                                    {{ single_price($order->grand_total) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ tran('Payment method') }}</td>
                                <td class="text-right">
                                    {{ tran(ucfirst(str_replace('_', ' ', $order->payment_type))) }}</td>
                            </tr>

                            <tr>
                                <td class="text-main text-bold">{{ tran('Additional Info') }}</td>
                                <td class="text-right">{{ $order->additional_info }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <hr class="new-section-sm bord-no">
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    <table class="invoice-summary table">
                        <thead>
                            <tr class="bg-trans-dark">
                                <th data-breakpoints="lg" class="min-col">#</th>
                                <th width="10%">{{ tran('Photo') }}</th>
                                <th class="text-uppercase">{{ tran('Variation') }}</th>
                                
                               
                                <th data-breakpoints="lg" class="text-uppercase">{{ tran('Delivery Type') }}</th>
                                <th data-breakpoints="lg" class="min-col text-uppercase text-center">
                                    {{ tran('Qty') }}
                                </th>
                                <th data-breakpoints="lg" class="min-col text-uppercase text-center">
                                    {{ tran('Price') }}</th>
                                <th data-breakpoints="lg" class="min-col text-uppercase text-right">
                                    {{ tran('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                            @foreach ($order->orderDetails as $key => $orderDetail)
                                <?php
                                $order_color=App\Models\Color::where('name',$orderDetail->variation)->first();
                                ?>
                                <tr>
                                    <td>{{ $key + 1  }}</td>
                                    <td>
                                        @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                                        <?php
                                        if($orderDetail->selectedimg !=""){
                                       $img=asset('assets/images/products/'.$orderDetail->selectedimg);
                                        }else{
                                            $img=asset('assets/images/products/'.$orderDetail->product->thumbnail_img);
                                        }
                                        ?>
                                        <a href="{{ route('product.details', $orderDetail->product->slug) }}"
                                                target="_blank"><img height="50"
                                                    src="{{ $img }}"></a>
                                        @elseif ($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                            <a href="{{ route('auction-product', $orderDetail->product->slug) }}"
                                                target="_blank"><img height="50"
                                                    src="{{ $img }}"></a>
                                        @else
                                            <strong>{{ tran('N/A') }}</strong>
                                        @endif
                                    </td>
                                    
                                  
                            
                                    <td>
                                        @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                                            <strong><a href="{{ route('product.details', $orderDetail->product->slug) }}"
                                                    target="_blank"
                                                    class="text-muted">{{ $orderDetail->product->name }}</a></strong>
                                            <br><small>{{ $orderDetail->variation }}</small>
                                        @elseif ($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                            <strong><a href="{{ route('auction-product', $orderDetail->product->slug) }}"
                                                    target="_blank"
                                                    class="text-muted">{{ $orderDetail->product->name }}</a></strong>
                                        @else
                                            <strong>{{ tran('Product Unavailable') }}</strong>
                                        @endif
                                    </td>
                                   
                                   
                                    <td>
                                        @if ($order->shipping_type != null && $order->shipping_type == 'home_delivery')
                                            {{ tran('Home Delivery') }}
                                        @elseif ($order->shipping_type == 'pickup_point')
                                            @if ($order->pickup_point != null)
                                                {{ $order->pickup_point->getTranslation('name') }}
                                                ({{ tran('Pickup Point') }})
                                            @else
                                                {{ tran('Pickup Point') }}
                                            @endif
                                        @elseif($order->shipping_type == 'carrier')
                                            @if ($order->carrier != null)
                                                {{ $order->carrier->name }} ({{ tran('Carrier') }})
                                                <br>
                                                {{ tran('Transit Time').' - '.$order->carrier->transit_time }}
                                            @else
                                                {{ tran('Carrier') }}
                                            @endif
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $orderDetail->quantity }}</td>
                                    <td class="text-center">
                                        {{ single_price($orderDetail->price / $orderDetail->quantity) }}</td>
                                    <td class="text-center">{{ single_price($orderDetail->price) }}</td>
                               
                               
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if($shop)
             <div class="clearfix float-left">
                 <table class="table">
                    <tbody>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ tran('Shop Name') }} :</strong>
                            </td>
                            <td>
                                {{  $shop->name  }}
                            </td>
                             
                        </tr>
                        
                        <tr>
                            <td>
                                <strong class="text-muted">{{ tran('Shop Address') }} :</strong>
                            </td>
                            <td>
                                {{  $shop->address  }}
                            </td>   
                        </tr>
                        </table>    
                 </div>
                 @endif
            <div class="clearfix float-right">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ tran('Sub Total') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('price')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ tran('Tax') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('tax')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ tran('Shipping') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('shipping_cost')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ tran('Coupon') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->coupon_discount) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ tran('TOTAL') }} :</strong>
                            </td>
                            <td class="text-muted h5">
                                {{ single_price($order->grand_total) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
 
            </div>

        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $('#update_delivery_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_delivery_status').val();
            $.post('{{ route('seller.orders.update_delivery_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                $('#order_details').modal('hide');
                location.reload().setTimeOut(500);
            });
        });

        $('#update_payment_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_payment_status').val();
            $.post('{{ route('seller.orders.update_payment_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                $('#order_details').modal('hide');
                location.reload().setTimeOut(500);
            });
        });
    </script>
@endpush
