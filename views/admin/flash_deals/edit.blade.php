@extends('layouts.admin')

@section('content')

<div class="page-header">
            <h3 class="page-title">
              {{tran('All Flash Deals')}}
              <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{tran('Flash Deal Information')}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{tran('Flash Deal Information')}}</li>
              </ol>
            </nav>
            </h3>
          </div>

<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card">
            <div class="card-body p-0">
             
              <form class="p-4" action="{{ route('flash_deals.update', $flash_deal->id) }}" method="POST"  enctype="multipart/form-data">
                @csrf
                  <input type="hidden" name="_method" value="PATCH">
                  <input type="hidden" name="lang" value="{{ $lang }}">

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{tran('Title')}}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{tran('Title')}}" id="name" name="title" value="{{ $flash_deal->title }}" class="form-control" required>
                        </div>
                    </div>

                    @php
                      $start_date = date('d-m-Y H:i A', $flash_deal->start_date);
                      $end_date = date('d-m-Y H:i A', $flash_deal->end_date);
                    @endphp

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="start_date">{{tran('Date')}}</label>
                        <div class="col-sm-9">
                        <input type="hidden" class="form-control" id="date_discount" value="{{ $start_date.' - '.$end_date }}">

                          <input type="text" class="form-control" value="{{ $start_date.' to '.$end_date }}"  id="date_range" name="date_range" placeholder="{{ tran('Select Date') }}" data-time-picker="true" data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="products">{{tran('Products')}}</label>
                        <div class="col-sm-9">
                            <select name="products[]" id="products" onchange="get_flash_deal_discount()" class="form-control select2"  multiple required data-placeholder="{{ tran('Choose Products') }}">
                                @foreach(\App\Models\Product::where('published', 1)->where('approved', 1)->get() as $product)
                                    @php
                                        $flash_deal_product = \App\Models\FlashDealProduct::where('flash_deal_id', $flash_deal->id)->where('product_id', $product->id)->first();
                                    @endphp
                                    <option value="{{$product->id}}" <?php if($flash_deal_product != null) echo "selected";?> >{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-danger">
                        {{ tran('If any product has discount or exists in another flash deal, the discount will be replaced by this discount & time limit.') }}
                    </div>

                    <br>
                    <div class="form-group" id="discount_table">

                    </div>

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{tran('Save')}}</button>
                    </div>
                  </form>
              </div>
        </div>
    </div>
</div>

@endsection

@push('js')
    <script type="text/javascript">
            get_flash_deal_discount();
            function get_flash_deal_discount(){
                var product_ids = $('#products').val();
                if(product_ids.length > 0){
                    $.post('{{ route('flash_deals.product_discount_edit') }}', {_token:'{{ csrf_token() }}', product_ids:product_ids, flash_deal_id:{{ $flash_deal->id }}}, function(data){
                        $('#discount_table').html(data);
                        //AIZ.plugins.fooTable();
                    });
                }
                else{
                    $('#discount_table').html(null);
                }
            }
    </script>

    
<script>  
   $("#date_range").daterangepicker({
      timePicker: true,
    //  autoUpdateInput: autoUpdateInput_val,
     startDate: moment().startOf('hour'),
     endDate: moment().startOf('hour').add(32, 'hour'),
     locale: {
      format: 'DD-MM-YYYY hh:mm A'
    }
  });
  
$("#date_range").val($("#date_discount").val());
</script>

@endpush
