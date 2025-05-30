@extends('layouts.site')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<section class="section-box shop-template mt-30">
        <div class="container box-account-template"> 
          <div class="box-tabs mb-100">  
<div class="aiz-titlebar mb-4">
        <div class="h6 fw-700">
            @if ($conversation->sender_id == Auth::user()->id && $conversation->receiver->shop != null)
                <a href="{{ route('shop.visit', $conversation->receiver->shop->slug) }}" class="">{{ $conversation->receiver->shop->name }}</a>
            @endif
        </div>
    </div>
    @error("file") 
          <div class="alert alert-danger print-error-msg">
            <ul class="m-0"><li>    
            <span>{{$message}}</span>
                            </li></ul>
                        </div>
                        @enderror
            
    <div class="col-lg-12" id="messages">
    @include('front.includes.partials_messages',$conversation)
    </div>  
    </div>
    </div>
    </div>
    </section>
@endsection

@push('js')
    <script type="text/javascript">
    function refresh_messages(){
        $.post('{{ route('customer.conversations.refresh') }}', {_token:'{{ @csrf_token() }}', id:'{{ encrypt($conversation->id) }}'}, function(data){
            $('#messages').html(data);
        })
    }

    refresh_messages(); // This will run on page load
    setInterval(function(){
        refresh_messages() // this will run after every 50 seconds
    }, 50000);
    </script>


@endpush
