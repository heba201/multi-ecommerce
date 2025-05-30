@extends('seller.layouts.app')

@section('content')
<div class="page-header">
            <h3 class="page-title">
            {{tran('Product Queries')}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{tran('Product Queries')}}</a></li>
                <li class="breadcrumb-item active" aria-current="page"> {{tran('Show')}}</li>
                </ol>
            </nav>
            </h3>
          </div>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    {{ $query->product->name }}
                </h5>
            </div>

            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0">
                        <div class="media mb-2">
                        <?php
                            if($query->user != null && $query->user->avatar_original !=""){
                                $avatar=asset('assets/images/users/'.$query->user->avatar_original);
                            }else  $avatar=asset('assets/images/users/avatar.png');
                            ?>
                            <img class="avatar avatar-xs mr-3" src="{{ $avatar }}">
                            <div class="media-body">
                                <h6 class="mb-0 fw-600">
                                    @if ($query->user != null)
                                        {{ $query->user->name }}
                                    @endif
                                </h6>
                                <p class="opacity-50">{{ $query->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <p>
                            {{ strip_tags($query->question) }}
                        </p>
                    </li>
                </ul>
                @if (Auth::user()->id == $query->seller_id)
                    <form action="{{ route('seller.product_query.reply',$query->id) }}" method="POST">
                        @method('put')
                        @csrf
                        <input type="hidden" name="conversation_id" value="{{ $query->id }}">
                        <div class="row">
                            <div class="col-md-12">
                                <textarea class="form-control" rows="4" name="reply" placeholder="{{ tran('Type your reply') }}"
                                    required></textarea>
                            </div>
                        </div>
                        <br>
                        <div class="text-right">
                            <button type="submit" class="btn btn-info">{{ tran('Send') }}</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
