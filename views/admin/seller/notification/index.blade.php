@extends('seller.layouts.app')

@section('content')

<div class="content-wrapper">
          <div class="page-header">
            <h3 class="page-title">
            {{tran('All Notifications')}}
            </h3>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{tran('Home')}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{tran('All Notifications')}}</li>
              </ol>
            </nav>
          </div>
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">
                 
                    <table id="order-listing" class="table">
                      <thead>
                        <tr>
                        <th>{{ tran('Order code ') }}</th> 
                        <th>{{ tran('Date') }}</th>
                        <th>{{ tran('Actions') }}</th>
                        </tr>
                      </thead>
                      <tbody>
                      @forelse($notifications->sortBy('created_at')  as $notification)
                      @if($notification->type == 'App\Notifications\OrderNotification')
                      <tr>
                            <td> {{$notification->data['order_code']}}</td>
                            <td> {{  $notification->created_at->format('Y-m-d') .' '. $notification->created_at->format(' H:i:s')}}</td>
                            <td>
                              <a class="btn btn-outline-primary" href="{{route('orders.show', encrypt($notification->data['order_id']))}}">{{tran('View')}}</a>
                            </td>
                        </tr>
                        @endif
                        @empty
                           
                        @endforelse   
                      </tbody>
                    </table>

                    
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection


