@extends('layouts.admin')
@section('content')
<style>
    .btn-outline-primary{
        padding:10px !important;
    }
    </style>
          <div class="page-header">
            <h3 class="page-title">
            {{tran('Brands')}}
              <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{tran('Brands')}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{tran('Show All')}}</li>
              </ol>
            </nav>
            </h3>
            
          </div>
          <div class="card">
          @include('admin.includes.alerts.success')
          @include('admin.includes.alerts.errors')
            <div class="card-body">
              <h4 class="card-title"></h4>
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">
                    <table id="order-listing" class="table">
                      <thead>
                        <tr>
                        <th>{{tran('Name')}}</th>
		                    <th>{{tran('Logo')}}</th>
                        <th>{{tran('Options')}}</th>
                        </tr>
                      </thead>
                      <tbody>
                      @isset($brands)
                    @foreach($brands as $brand)
                                                  

                         <tr>
		                        <td>{{ $brand->name }}</td>
								                  <td>
                                  @if(count(explode("brands/",$brand->logo)) > 1) <img src="{{ $brand->logo }}" alt="{{tran('Brand')}}" class="h-50px">@endif
		                        </td>
		                        <td>
                            @can('edit_brand')
                  <a class="btn btn-light" style="padding:10px;"  href="{{route('admin.brands.edit',$brand -> id)}}"><i class="fa-solid fa-pen-to-square"></i></a>
                  @endcan
                  @can('delete_brand')
                  <a href="#" class="btn btn-light confirm-delete" style="padding:10px;"  data-href="{{route('admin.brands.delete',$brand -> id)}}"><i class="fa-sharp fa-solid fa-trash"></i></a>
                  @endcan
                </td>
		                    </tr>
                        @endforeach
                        @endisset
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
    <!-- page-body-wrapper ends -->
    @stop
    @section('modal')
    @include('modals.delete_modal')
@endsection