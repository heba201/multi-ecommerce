@extends('seller.layouts.app')
@section('content')
<div class="page-header">
            <h3 class="page-title">
            {{tran('Coupons')}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{tran('Coupons')}}</a></li>
                <li class="breadcrumb-item active" aria-current="page"> {{tran('View All')}}</li>
                </ol>
            </nav>
            </h3>
          </div>
   
    <div class="row gutters-10 justify-content-center">
        <div class="col-md-4 mx-auto mb-3" >
            <a href="{{ route('coupon.create')}}">
            <div class="p-3 rounded mb-3 c-pointer text-center bg-white shadow-sm hov-shadow-lg has-transition">
                <span class="size-60px rounded-circle mx-auto bg-secondary d-flex align-items-center justify-content-center mb-3">
                <i class="fa-solid fa-plus"></i>
                </span>
                <div class="fs-18 text-primary">{{ tran('Add New Coupon') }}</div>
            </div>
            </a>
        </div>
    </div>
    <div class="card">
          @include('admin.includes.alerts.success')
          @include('admin.includes.alerts.errors')
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ tran('All Coupons') }}</h5>
            </div>
        </div>
        <div class="card-body">
            <table class="table p-0" id="order-listing"> 
                <thead>
                    <tr>
                        <th data-breakpoints="lg">#</th>
                        <th>{{tran('Code')}}</th>
                        <th data-breakpoints="lg">{{tran('Type')}}</th>
                        <th data-breakpoints="lg">{{tran('Start Date')}}</th>
                        <th data-breakpoints="lg">{{tran('End Date')}}</th>
                        <th>{{tran('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coupons as $key => $coupon)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>{{$coupon->code}}</td>
                            <td>@if ($coupon->type == 'cart_base')
                                    {{ tran('Cart Base') }}
                                @elseif ($coupon->type == 'product_base')
                                    {{ tran('Product Base') }}
                            @endif</td>
                            <td>{{ date('d-m-Y', $coupon->start_date) }}</td>
                            <td>{{ date('d-m-Y', $coupon->end_date) }}</td>
                            <td class="text-right">
                                <a class="btn btn-light btn-light btn-sm"   style="padding:10px;" href="{{route('coupon.edit', encrypt($coupon->id) )}}" title="{{ tran('Edit') }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-light btn-sm confirm-delete"   style="padding:10px;" data-href="{{route('seller.coupon.destroy', $coupon->id)}}" title="{{ tran('Delete') }}">
                                <i class="fa-sharp fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
