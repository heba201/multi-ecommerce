@extends('layouts.admin')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="align-items-center">
        <h1 class="h3">{{tran('All Customers')}}</h1>
    </div>
</div>
          @include('admin.includes.alerts.success')
          @include('admin.includes.alerts.errors') 
<div class="card">
        <div class="card-body">
            <table class="table mb-0" id="order-listing">
                <thead>
                    <tr>
                        <th>{{tran('Name')}}</th>
                        <th data-breakpoints="lg">{{tran('Email Address')}}</th>
                        <th data-breakpoints="lg">{{tran('Phone')}}</th>
                        <th class="text-right">{{tran('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                        @if ($user != null)
                            <tr>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td>{{$user->phone}}</td>
                                
                                <td class="text-right">
                                    @can('login_as_customer')
                                        <a style="padding:10px;"  href="{{route('customers.login', encrypt($user->id))}}" class="btn btn-light" title="{{ tran('Log in as this Customer') }}">
                                           {{tran('Log in as customer')}}
                                        </a>
                                    @endcan
                    
                                    @can('delete_customer')
                                        <a href="#"  class="btn btn-light confirm-delete"  style="padding:10px;"  data-href="{{route('customers.destroy', $user->id)}}" title="{{ tran('Delete') }}">
                                        <i class="fa-sharp fa-solid fa-trash"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $users->appends(request()->input())->links() }}
            </div>
        </div>
   
</div>


<div class="modal fade" id="confirm-ban">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h6">{{tran('Confirmation')}}</h5>
                <button type="button" class="close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{tran('Do you really want to ban this Customer?')}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">{{tran('Cancel')}}</button>
                <a type="button" id="confirmation" class="btn btn-primary">{{tran('Proceed!')}}</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
