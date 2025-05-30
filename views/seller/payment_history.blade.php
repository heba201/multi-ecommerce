@extends('seller.layouts.app')

@section('content')
<div class="aiz-titlebar mt-2 mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ tran('Payment History') }}</h1>
        </div>
      </div>
    </div>
    <div class="card" >
            <div class="card-body">
                <table class="table   mb-0" id="order-listing" >
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ trans('Date')}}</th>
                            <th>{{ trans('Amount')}}</th>
                            <th>{{ trans('Payment Method')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $key => $payment)
                            <tr>
                                <td>
                                    {{ $key+1 }}
                                </td>
                                <td>{{ date('d-m-Y', strtotime($payment->created_at)) }}</td>
                                <td>
                                    {{ single_price($payment->amount) }}
                                </td>
                                <td>
                                    {{ trans(ucfirst(str_replace('_', ' ', $payment->payment_method))) }} @if ($payment->txn_code != null) ({{  trans('TRX ID') }} : {{ $payment->txn_code }}) @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
            </div>
      
    </div>

@endsection
