@extends('seller.layouts.app')

@section('content')

<div class="aiz-titlebar mt-2 mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ tran('Commission History') }}</h1>
        </div>
      </div>
    </div>

    <div class="card">
        <form class="" action="" id="sort_commission_history" method="GET">
            <div class="card-header row gutters-5">
                <div class="col-md-5">
                <div class="form-group mb-0">
                        <input type="text" class="form-control form-control-sm aiz-date-range" id="search" name="date_range"@isset($date_range) value="{{ $date_range }}" @endisset placeholder="{{ tran('Date range') }}" autocomplete="off">
                    </div>
                  

                </div>
                <div class="col-md-6">
                <div class="form-group mb-0">
                <button type="submit" class="btn btn-primary">{{ tran('Filter') }}</button>
                </div>
                </div>
            </div>
        </form>
        <div class="card-body">
            <table class="table  mb-0" id="order-listing">
                <thead>
                    <tr>
                        <th>#</th>
                        <th data-breakpoints="lg">{{ tran('Order Code') }}</th>
                        <th>{{ tran('Admin Commission') }}</th>
                        <th>{{ tran('Earning') }}</th>
                        <th data-breakpoints="lg">{{ tran('Created At') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($commission_history as $key => $history)
                    <tr>
                        <td>{{ ($key+1) }}</td>
                        <td>
                            @if(isset($history->order))
                                {{ $history->order->code }}
                            @else
                                <span class="badge badge-inline badge-danger">
                                    {{ tran('Order Deleted') }}
                                </span>
                            @endif
                        </td>
                        <td>{{ $history->admin_commission }}</td>
                        <td>{{ $history->seller_earning }}</td>
                        <td>{{ $history->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
           
        </div>
    </div>
@endsection

@push('js')
<script type="text/javascript">
       $('input[name="date_range"]').daterangepicker({
      timePicker: true,
    //  autoUpdateInput: autoUpdateInput_val,
     startDate: moment().startOf('hour'),
     endDate: moment().startOf('hour').add(32, 'hour'),
     locale: {
      format: 'DD-MM-YYYY hh:mm A'
    }
  });
  $('input[name="date_range"]').val('');

  $('input[name="date_range"]').on('cancel.daterangepicker', function(ev, picker) {
    $('input[name="date_range"]').val('');
  });
    function sort_commission_history(el){
        $('#sort_commission_history').submit();
    }
</script>
@endpush
