
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <div class="aiz-titlebar mt-2 mb-4">
        <div class="h6">
            <span>{{ tran('Conversations With ')}}</span>
            @if ($conversation->sender_id == Auth::user()->id && $conversation->receiver->shop != null)
                <a href="{{ route('shop.visit', $conversation->receiver->shop->slug) }}" class="">{{ $conversation->receiver->shop->name }}</a>
            @endif
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title fs-16 fw-600 mb-0">#{{ $conversation->title }}
            (
                {{ tran('Between you and') }}
                @if ($conversation->sender_id == Auth::user()->id)
                    {{ $conversation->receiver->name }}
                @else
                    {{ $conversation->sender->name }}
                @endif
            )
            </h5>
        </div>

        <div class="card-body">
            <ul class="list-group list-group-flush">
                @foreach($conversation->messages as $message)
                    <li class="list-group-item px-0">
                        <div class="media mb-2">
                            <?php
                            if($message->user != null && $message->user->avatar_original !=""){
                                $avatar=asset('assets/images/users/'.$message->user->avatar_original);
                            }else  $avatar=asset('assets/images/users/avatar.png');
                            ?>
                          <img class="avatar avatar-xs mr-3"  src="{{ $avatar }}" >
                          <div class="media-body">
                            <h6 class="mb-0 fw-600">
                                @if ($message->user != null)
                                    {{ $message->user->name }}
                                @endif
                            </h6>
                            <p class="opacity-50">{{$message->created_at}}</p>
                          </div>
                        </div>
                        <p>
                            <?php 
                            $msg_arr=[];
                            if (strpos($message->message, "<br>") !== false) {
                                     $msg_arr=explode("<br>",$message->message);  
                            ?>
                               <a href="<?php echo $msg_arr[0];?>" target="_blank"><?php echo $msg_arr[0];?></a>
                        <br>
                        <?php echo $msg_arr[1];?>
                          <?php
                            }else{
                                echo $message->message;
                            }
                           
                           ?>
                        
                        </p>
                            @if($message->message_photo !="")
                        <img alt="" class="img-thumbnail" src="{{asset('assets/images/messages_photos/'.$message->message_photo)}}" width="200" height="200">
                        <br><br>
                        @endif
                        <p>
                            @if($message->voice_message !="")
                        <audio controls>
                        <source src="{{asset($message->voice_message)}}" type="audio/wav"></audio>
                      @endif
                        </p>
                    </li>
                @endforeach
            </ul> 
        </div>
    </div>

