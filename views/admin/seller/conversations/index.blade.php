@extends('seller.layouts.app')

@section('content')
<div class="aiz-titlebar mt-2 mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ tran('Conversations') }}</h1>
        </div>
      </div>
    </div>
<div class="card">
      <div class="card-body">
      <h5 class="mb-0 h6">{{ tran('Conversations')}}</h5>
        <ul class="list-group list-group-flush">
          @if($conversations ->count() > 0 )
          @foreach ($conversations as $key => $conversation)
          @if( $conversation->messages->count() > 0)
              @if ($conversation->receiver != null && $conversation->sender != null)
                    <li class="list-group-item px-0">
                      <div class="row gutters-10">
                          <div class="col-auto">
                              <div class="media">
                                  <span class="avatar avatar-sm flex-shrink-0">
                                    @if (Auth::user()->id == $conversation->sender_id)
                                        <img @if ($conversation->receiver->avatar_original == null) src="{{ asset('assets/images/users/avatar.png') }}" @else src="{{ asset('assets/images/users/'.$conversation->receiver->avatar_original) }}" @endif>
                                    @else
                                        <img @if ($conversation->sender->avatar_original == null) src="{{ asset('assets/images/users/avatar.png') }}" @else src="{{ asset('assets/images/users/'.$conversation->sender->avatar_original) }}" @endif class="rounded-circle">
                                    @endif
                                </span>
                              </div>
                          </div>
                          <div class="col-auto col-lg-3">
                              <p>
                                  @if (Auth::user()->id == $conversation->sender_id)
                                      <span class="fw-600">{{ $conversation->receiver->name }}</span>
                                  @else
                                      <span class="fw-600">{{ $conversation->sender->name }}</span>
                                  @endif
                                  <br>
                                  <span class="opacity-50">
                                      {{ date('h:i:m d-m-Y', strtotime($conversation->messages->last()->created_at)) }}
                                  </span>
                              </p>
                          </div>
                          <div class="col-12 col-lg">
                              <div class="block-body">
                                  <div class="block-body-inner pb-3">
                                      <div class="row no-gutters">
                                          <div class="col">
                                              <h6 class="mt-0">n.
                                                  <a href="{{ route('conversations.show', encrypt($conversation->id)) }}" class="text-dark fw-600">
                                                      {{ $conversation->title }}
                                                  </a>
                                                  @if ((Auth::user()->id == $conversation->sender_id && $conversation->sender_viewed == 0) || (Auth::user()->id == $conversation->receiver_id && $conversation->receiver_viewed == 0))
                                                      <span class="badge badge-inline badge-danger">{{ tran('New') }}</span>
                                                  @endif
                                              </h6>
                                          </div>
                                      </div>
                                      <p class="mb-0 opacity-50">
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
              @endif
          @endforeach
          @endif
      </ul>
      </div>
    </div>
    

@endsection
