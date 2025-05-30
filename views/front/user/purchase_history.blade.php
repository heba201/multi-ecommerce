@extends('layouts.site')
@section('content')
<section class="section-box shop-template mt-30">
        <div class="container box-account-template">
          <h3>Hello {{auth()->user()->name}}</h3>
          <p class="font-md color-gray-500">From your account dashboard. you can easily check & view your recent orders,<br class="d-none d-lg-block">manage your shipping and billing addresses and edit your password and account details.</p>
          <div class="box-tabs mb-100">
            <ul class="nav nav-tabs nav-tabs-account" role="tablist">
              <li><a class="active" href="#tab-orders" data-bs-toggle="tab" role="tab" aria-controls="tab-orders" aria-selected="true">Orders</a></li>

            </ul>
            <div class="border-bottom mt-20 mb-40"></div>
            <div class="tab-content mt-30">
             
            
              <div class="tab-pane fade active show" id="tab-orders" role="tabpanel" aria-labelledby="tab-orders">
              @foreach ($orders as $key => $order)
                        
              <div class="box-orders">
                  <div class="head-orders">
                    <div class="head-left">
                      <h5 class="mr-20">Order ID: {{ $order->code }}</h5><span class="font-md color-brand-3 mr-20">{{date('d-m-Y', $order->date) }}</span><span class="label-delivery">  {{ tran(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}</span> @if($order->delivery_viewed == 0)
                  <span class="ml-2" style="color:green"><strong> * </strong></span>
                                    @endif
                    </div>
                    <div class="head-right"><a href="{{route('purchase_history.details', encrypt($order->id))}}" class="btn btn-buy font-sm-bold w-auto">View Order</a></div>
                  </div>
                  @if (count($order->orderDetails) > 0)
                  <div class="body-orders">
                    <div class="list-orders">
                    @foreach ($order->orderDetails as $key => $orderDetail)
                    <div class="item-orders">
                      <?php
                      $img="";
                      if($orderDetail->product->thumbnail_img !=""){
                        $img=asset('assets/images/products/'.$orderDetail->product->thumbnail_img);
                      }else{
                        if($orderDetail->product-> images()->count() > 0){
                          $img= $pro -> images[0] -> photo ;
                         }
                      }
                      ?>
                        <div class="image-orders"><img src="{{$img}}" alt="Ecom"></div>
                        <div class="info-orders">
                          <h5>{{ $orderDetail->product->name }}</h5>
                        </div>
                        <div class="quantity-orders">
                          <h5>Quantity: {{ $orderDetail->quantity }}</h5>
                        </div>
                        <div class="price-orders">
                          <h3>{{single_price($orderDetail->price + $orderDetail->tax)}}</h3>
                        </div>
                      </div>
                      @endforeach
                    </div>
                  </div>
                  @endif
                </div>
                @endforeach
                <nav>
                  <ul class="pagination">
                  {{ $orders->links() }}
                  </ul>
                </nav>
              </div>
            </div>
          </div>
        </div>
      </section>
    @endsection