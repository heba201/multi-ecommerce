@extends('seller.layouts.app')
@section('content')

    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ tran('Edit Your Coupon') }}</h1>
            </div>
        </div>
    </div>

    <div class="row gutters-5">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 h6">{{tran('Coupon Information Update')}}</h3>
                </div>
                <form action="{{ route('coupon.update', $coupon->id) }}" method="POST">
                    <input name="_method" type="hidden" value="PATCH">
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
                    <div class="card-body">
                        <input type="hidden" name="id" value="{{ $coupon->id }}" id="id">
                        <div class="form-group row">
                            <label class="col-lg-3 col-from-label" for="name">{{tran('Coupon Type')}}</label>
                            <div class="col-lg-9">
                                <select name="type" id="coupon_type" class="form-control" onchange="coupon_form()" required>
                                    @if ($coupon->type == "product_base")
                                        <option value="product_base" selected>{{tran('For Products')}}</option>
                                    @elseif ($coupon->type == "cart_base")
                                        <option value="cart_base">{{tran('For Total Orders')}}</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div id="coupon_form">

                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary">{{tran('Save')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection
@push('js')
<script type="text/javascript">

    function coupon_form(){
        var coupon_type = $('#coupon_type').val();
        var id = $('#id').val();
		$.post('{{ route('seller.coupon.get_coupon_form_edit') }}',{_token:'{{ csrf_token() }}', coupon_type:coupon_type, id:id}, function(data){
            $('#coupon_form').html(data);

		});
    }

    $(document).ready(function(){
        coupon_form();
    });
</script>
@endpush
