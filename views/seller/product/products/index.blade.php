@extends('seller.layouts.app')

@section('content')

<div class="page-header">
            <h3 class="page-title">
            {{tran('Products')}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{tran('Products')}}</a></li>
                <li class="breadcrumb-item active" aria-current="page"> {{tran('Show All')}}</li>
                </ol>
            </nav>
            </h3>
            
          </div>

    <div class="row gutters-10 justify-content-center">
       

        <div class="col-md-4 mx-auto mb-3" >
            <a href="{{ route('seller.products.create')}}">
              <div class="p-3 rounded mb-3 c-pointer text-center bg-white shadow-sm hov-shadow-lg has-transition">
                  <span class="size-60px rounded-circle mx-auto bg-secondary d-flex align-items-center justify-content-center mb-3">
                  <i class="fa-solid fa-plus"></i>
                  </span>
                  <div class="fs-18 text-primary">{{ tran('Add New Product') }}</div>
              </div>
            </a>
        </div>


    </div>

    <div class="card">
          @include('admin.includes.alerts.success')
          @include('admin.includes.alerts.errors')
        <form class="" id="sort_products" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-md-0 h6">{{ tran('All Products') }}</h5>
                </div>
            </div>
            <div class="card-body">
                <table class="table" id="order-listing">
                    <thead>
                        <tr>
                         
                            <th width="30%">{{ tran('Name')}}</th>
                            <th data-breakpoints="md">{{ tran('Category')}}</th>
                            <th data-breakpoints="md">{{ tran('Current Qty')}}</th>
                            <th>{{ tran('Base Price')}}</th>
                            @if(get_setting('product_approve_by_admin') == 1)
                                <th data-breakpoints="md">{{ tran('Approval')}}</th>
                            @endif
                            <th data-breakpoints="md">{{ tran('Published')}}</th>
                            <th data-breakpoints="md">{{ tran('Featured')}}</th>
                            <th data-breakpoints="md" class="text-right">{{ tran('Options')}}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($products as $key => $product)
                            <tr>
                                <td>
                                    <div class="row gutters-5 w-200px w-md-300px mw-100">
                                <div class="col-auto">
                                    <img src="{{ asset('assets/images/products/'.$product->thumbnail_img)}}" alt="Image" class="size-50px img-fit">
                                </div>
                                <div class="col">
                                <a href="{{ route('product.details', $product->slug) }}" target="_blank" class="text-reset">
                                        {{ $product->name}}
                                    </a>
                                </div>
                            </div>
                                </td>
                                <td>
                                    @if ($product->category != null)
                                        {{ $product->category->name }}
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $qty = 0;
                                        foreach ($product->stocks as $key => $stock) {
                                            $qty += $stock->qty;
                                        }
                                        echo $qty;
                                    @endphp
                                </td>
                                <td>{{ $product->unit_price }}</td>
                                @if(get_setting('product_approve_by_admin') == 1)
                                    <td>
                                        @if ($product->approved == 1)
                                            <span class="badge badge-inline badge-success">{{ tran('Approved')}}</span>
                                        @else
                                            <span class="badge badge-inline badge-info">{{ tran('Pending')}}</span>
                                        @endif
                                    </td>
                                @endif
                                <td>
                                    <label class="chk-switch chk-switch-success mb-0">
                                        <input onchange="update_published(this)" value="{{ $product->id }}" type="checkbox" <?php if($product->published == 1) echo "checked";?> >
                                        <span></span>
                                    </label>
                                </td>
                                <td>
                                    <label class="chk-switch chk-switch-success mb-0">
                                        <input onchange="update_featured(this)" value="{{ $product->id }}" type="checkbox" <?php if($product->seller_featured == 1) echo "checked";?> >
                                        <span></span>
                                    </label>
                                </td>
                                <td class="text-right">
                                <a class="btn btn-light btn-sm" style="padding:10px" href="{{route('seller.products.edit', ['id'=>$product->id, 'lang'=>env('DEFAULT_LANGUAGE')])}}" title="{{ tran('Edit') }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                               
                                <a href="#"  style="padding:10px"  class="btn btn-soft-danger btn-light btn-sm confirm-delete" data-href="{{route('seller.products.destroy', $product->id)}}" title="{{ tran('Delete') }}">
                                <i class="fa-sharp fa-solid fa-trash"></i>
                                </a>
                            </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
               
            </div>
        </form>
    </div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@push('js')
    <script type="text/javascript">

        $(document).on("change", ".check-all", function() {
            if(this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;                        
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;                       
                });
            }
          
        });

        function update_featured(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('seller.products.featured') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ tran('Featured products updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ tran('Something went wrong') }}');
                    location.reload();
                }
            });
        }

        function update_published(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('seller.products.published') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ tran('Published products updated successfully') }}');
                }
                else if(data == 2){
                    AIZ.plugins.notify('danger', '{{ tran('Please upgrade your package.') }}');
                    location.reload();
                }
                else{
                    AIZ.plugins.notify('danger', '{{ tran('Something went wrong') }}');
                    location.reload();
                }
            });
        }

        function bulk_delete() {
            var data = new FormData($('#sort_products')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('seller.products.bulk-delete')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response == 1) {
                        location.reload();
                    }
                }
            });
        }

    </script>
@endpush
