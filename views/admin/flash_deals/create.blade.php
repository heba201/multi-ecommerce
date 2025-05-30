@extends('layouts.admin')

@section('content')

<div class="page-header">
            <h3 class="page-title">
              {{tran('Flash Deals')}}
              <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{tran('Flash Deals')}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{tran('Add New Flash Deal')}}</li>
              </ol>
            </nav>
            </h3>
          </div>

<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{tran('Flash Deal Information')}}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('flash_deals.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="name">{{tran('Title')}}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{tran('Title')}}" id="name" name="title" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-sm-3 control-label" for="start_date">{{tran('Date')}}</label>
                        <div class="col-sm-9">
                          <input type="text" class="form-control" name="date_range" placeholder="{{__('Select Date')}}" required>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label class="col-sm-3 control-label" for="products">{{tran('Products')}}</label>
                        <div class="col-sm-9">
                            <select name="products[]" id="products" onchange="discount()" class="select2 form-control" multiple required data-placeholder="{{ tran('Choose Products') }}" data-live-search="true" data-selected-text-format="count">
                                @foreach(\App\Models\Product::where('published', 1)->where('approved', 1)->orderBy('created_at', 'desc')->get() as $product)
                                    <option value="{{$product->id}}">{{ $product->name }}</option>
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
        function discount(){
                var product_ids = $('#products').val();
                if(product_ids.length > 0){
                    $.post('{{ route('flash_deals.product_discount') }}', {_token:'{{ csrf_token() }}', product_ids:product_ids}, function(data){
                        $('#discount_table').html(data);
                       // AIZ.plugins.fooTable();
                    });
                }
                else{
                    $('#discount_table').html(null);
                }
            }
        
    </script>


<script>   
//$(function() {
   // $('input[name="date_range"]').on('focus' ,function(){
        $('input[name="date_range"]').daterangepicker({
      timePicker: true,
    //  autoUpdateInput: autoUpdateInput_val,
     startDate: moment().startOf('hour'),
     endDate: moment().startOf('hour').add(32, 'hour'),
     locale: {
      format: 'DD-MM-YYYY hh:mm A'
    }
  });
   // })
//});
</script>
@endpush
