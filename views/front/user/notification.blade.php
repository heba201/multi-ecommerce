@extends('layouts.site')
@section('content')
<section class="section-box shop-template mt-30">
        <div class="container box-account-template">
          <h3>Hello {{auth()->user()->name}}</h3>
          <div class="box-tabs mb-100">
            <ul class="nav nav-tabs nav-tabs-account" role="tablist">
              <li><a class="active" href="#tab-notification" data-bs-toggle="tab" role="tab" aria-controls="tab-notification" aria-selected="true">Notification</a></li>
              
            </ul>
            <div class="border-bottom mt-20 mb-40"></div>
            <div class="tab-content mt-30">
              <div class="tab-pane fade active show" id="tab-notification" role="tabpanel" aria-labelledby="tab-notification">
                <div class="list-notifications">
                    <?php
                foreach($notifications as $key=>$notification){
                if($notification->type == 'App\Notifications\OrderNotification') {?>
                
                <div class="item-notification">
                    <div class="info-notification">  
                      <p class="font-md color-brand-3">{{trans('Your Order: ')}} <a href="{{route('purchase_history.details', encrypt($notification->data['order_id']))}}">{{$notification->data['order_code']}}
                                        </a>     {{tran(' has been '. ucfirst(str_replace('_', ' ', $notification->data['status'])))}}</p>
                    </div>
                    <div class="button-notification"><a class="btn btn-buy w-auto" href="{{route('purchase_history.details', encrypt($notification->data['order_id']))}}">View Details</a></div>
                                      </div>
                 <?php
                }
            }
                 ?>

                  
                </div>
                <nav>
                {{ $notifications->links() }}
                </nav>
              </div>
            </div>
          </div>
        </div>
      </section>
    @endsection