@extends('layouts.site')
@section('content')
<section class="section-box shop-template mt-30">
        <div class="container box-account-template"> 
          <div class="box-tabs mb-100">
            <ul class="nav nav-tabs nav-tabs-account" role="tablist">
              <li><a class="active" href="#tab-conversations" data-bs-toggle="tab" role="tab" aria-controls="tab-conversations" aria-selected="true">{{tran('Conversations')}}</a></li>
            </ul>
            <div class="border-bottom mt-20 mb-40"></div>
            <div class="tab-content mt-30">
              <div class="tab-pane fade active show" id="tab-conversations" role="tabpanel" aria-labelledby="tab-conversations">
                <div class="list-notifications">
             <!-- Conversations -->
    @if (count($conversations) > 0)
        <div >
            <ul class="list-group list-group-flush p-0">
                @foreach ($conversations as $key => $conversation)
                    @if ($conversation->receiver != null && $conversation->sender != null)
                        <li class="list-group-item p-4 has-transition hov-bg-light border mb-3">
                            <div class="row gutters-10">
                                <!-- Receiver/Shop Image -->
                                <div class="col-auto">
                                    <div class="media">
                                      
                                        <span class="avatar avatar-sm flex-shrink-0 border">
                                            @if (Auth::user()->id == $conversation->sender_id)
                                                @if ($conversation->receiver->shop != null)
                                                <?php
                                    if($conversation->receiver->shop->logo !=""){
                                    $logo=asset('assets/images/shops/'.$conversation->receiver->shop->logo);
                                    }else{
                                    $logo=asset('front/assets/imgs/default.png');
                                    }

                                ?>
                                                    <a href="{{ route('shop.visit', $conversation->receiver->shop->slug) }}" class="">
                                                        <img src="{{$logo}}" 
                                                            >
                                                    </a>
                                                @else
                                                    <img @if ($conversation->receiver->avatar_original == null) src="{{ asset('assets/images/users/avatar.png') }}" 
                                                        @else src="{{ asset('assets/images/users/'.$conversation->receiver->avatar_original) }}" @endif 
                                                        >
                                                @endif
                                            @else
                                                <img @if ($conversation->sender->avatar_original == null) src="{{ asset('assets/images/users/avatar.png') }}" @else src="{{ asset('assets/images/users/'.$conversation->sender->avatar_original) }}" @endif class="rounded-circle" >
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <!-- Receiver/Shop Name & Time -->
                                <div class="col-auto col-lg-3">
                                        @if (Auth::user()->id == $conversation->sender_id)
                                            <h6 class="text-dark mb-2">
                                                @if ($conversation->receiver->shop != null)
                                                    <a href="{{ route('shop.visit', $conversation->receiver->shop->slug) }}" class="text-reset hov-text-primary fw-700 fs-14">{{ $conversation->receiver->shop->name }}</a>
                                                @else
                                                    <span class="text-dark fw-700 fs-14 mb-2">{{ $conversation->receiver->name }}</span>
                                                @endif
                                            </h6>
                                        @else
                                            <h6 class="text-dark fw-700 fs-14 mb-2">{{ $conversation->sender->name }}</h6>
                                        @endif
                                        <small class="text-secondary fs-12">
                                            {{ date('d.m.Y h:i:m', strtotime($conversation->messages->last()->created_at)) }}
                                        </small>
                                </div>
                                <!-- conversation -->
                                <div class="col-12 col-lg">
                                    <div class="block-body">
                                        <div class="block-body-inner pb-3">
                                            <!-- Title -->
                                            <div class="row no-gutters">
                                                <div class="col">
                                                    <h6 class="mt-0">
                                                        <a href="{{ route('customer.conversations.show', encrypt($conversation->id)) }}" class="text-reset hov-text-primary fs-14 fw-700">
                                                            {{ $conversation->title }}
                                                        </a>
                                                        @if ((Auth::user()->id == $conversation->sender_id && $conversation->sender_viewed == 0) || (Auth::user()->id == $conversation->receiver_id && $conversation->receiver_viewed == 0))
                                                            <span class="badge badge-inline badge-danger">{{ tran('New') }}</span>
                                                        @endif
                                                    </h6>
                                                </div>
                                            </div>
                                            <!-- Last Message -->
                                            <p class="mb-0 text-secondary fs-14">
                                            <?php
                                        if (strpos($conversation->messages->last()->message, "<br>") !== false) {
                                            $msg_arr=explode("<br>",$conversation->messages->last()->message);  
                                            ?>
                                           
                                            <a href="<?php echo $msg_arr[0];?>" target="_blank"><?php echo $msg_arr[0];?></a>
                                            <br>
                                      <?php echo $msg_arr[1];?>
                                       <?php
                                        }else{
                                            echo $conversation->messages->last()->message;
                                        }
                                        ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    @else
        <div class="row">
            <div class="col">
                <div class="text-center bg-white p-4 border">
                    <img  src="{{ asset('assets/img/nothing.png') }}" alt="Image">
                    <h5 class="mb-0 h5 mt-3">{{ tran("There isn't anything added yet")}}</h5>
                </div>
            </div>
        </div>
    @endif
    <!-- Pagination -->
    <div class="pagination">
      	{{ $conversations->links() }}
    </div>
                   
                </div>
                
              </div>
        
            </div>
          </div>
        </div>
      </section>
@endsection
