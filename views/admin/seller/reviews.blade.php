@extends('seller.layouts.app')

@section('content')
    
<div class="page-header">
            <h3 class="page-title">
            {{tran('Products')}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{tran('Products')}}</a></li>
                <li class="breadcrumb-item active" aria-current="page"> {{tran('Product Reviews')}}</li>
                </ol>
            </nav>
            </h3>
            
          </div>

    <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ tran('Product Reviews') }}</h5>
            </div>
        <div class="card-body">
       
            <table class="table mb-0" id="order-listing">
                <thead>
                    <tr>
                       
                        <th>{{ tran('Product')}}</th>
                        <th data-breakpoints="lg">{{ tran('Customer')}}</th>
                        <th>{{ tran('Rating')}}</th>
                        <th data-breakpoints="lg">{{ tran('Comment')}}</th>
                        <th data-breakpoints="lg">{{ tran('Published')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reviews as $key => $value)
                        @php
                            $review = \App\Models\Review::find($value->id);
                        @endphp
                        @if($review != null && $review->product != null && $review->user != null)
                            <tr>
                                
                                <td>
                                    <a href="{{ route('product.details', $review->product->slug) }}" target="_blank">{{  $review->product->name}}</a>
                                </td>
                                <td>{{ $review->user->name }}</td>
                                <td>
                                    <span class="rating rating-sm">
                                        @for ($i=0; $i < $review->rating; $i++)
                                            <i class="fa-solid fa-star active"></i>
                                        @endfor
                                        @for ($i=0; $i < 5-$review->rating; $i++)
                                            <i class="fa-solid fa-star"></i>
                                        @endfor
                                    </span>
                                </td>
                                <td>{{ $review->comment }}</td>
                                <td>
                                    @if ($review->status == 1)
                                        <span class="badge badge-inline badge-success">{{  tran('Published') }}</span>
                                    @else
                                        <span class="badge badge-inline badge-danger">{{  tran('Unpublished') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            
        </div>
    </div>

@endsection
