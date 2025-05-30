@extends('seller.layouts.app')
@section('content')

<div class="page-header">
            <h3 class="page-title">
            {{tran('Coupons')}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{tran('Coupons')}}</a></li>
                <li class="breadcrumb-item active" aria-current="page"> {{tran('Add Your Coupon')}}</li>
                </ol>
            </nav>
            </h3>
          </div>

    <div class="row gutters-5">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{tran('Coupon Information Adding')}}</h5>
                </div>
                
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('coupon.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mt-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label" for="name">{{tran('Coupon Type')}}</label>
                            <div class="col-lg-9">
                                <select name="type" id="coupon_type" class="form-control" onchange="coupon_form()" required>
                                    <option value="">{{tran('Select One') }}</option>
                                    <option value="product_base" @if (old('type') == 'product_base') selected @endif>{{tran('For Products')}}</option>
                                    <option value="cart_base" @if (old('type') == 'cart_base') selected @endif>{{tran('For Total Orders')}}</option>
                                </select>
                            </div>
                        </div>

                        <div id="coupon_form">

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
    function coupon_form(){
        var coupon_type = $('#coupon_type').val();
		$.post('{{ route('seller.coupon.get_coupon_form') }}',{_token:'{{ csrf_token() }}', coupon_type:coupon_type}, function(data){
            $('#coupon_form').html(data);
		});
    }

    @if($errors->any())
        coupon_form();
    @endif

</script>
@endpush
