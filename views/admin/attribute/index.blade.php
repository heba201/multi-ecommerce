@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<style>
      * {
        font-size:16px !important;
    }
    .badge{
        background-color:#a3a1a2 !important;
    }
    .content-wrapper {
    margin-top:-100px !important
    }

    .sidebar  {
    margin-top:-100px !important
    }
   .sidebar .badge {
       background-color:#04B76B !important;
       font-size: 0.8125rem !important;
    }
    .sidebar .badge-success {
    border: 1px solid #04B76B !important;
    color: #ffffff;
}
.sidebar .nav .nav-item .nav-link .menu-title {
    color: inherit !important;
    display: inline-block !important;
    font-size: 0.875rem !important;
    line-height: 1 !important;
    vertical-align: middle !important;
    font-weight: 400 !important;
}

.sidebar .nav .nav-item .nav-link i.menu-arrow {
    margin-left: auto !important;
    line-height: 1 !important;
}
</style>
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3">{{ tran('All Attributes') }}</h1>
        </div>
    </div>

    <div class="row">
        <div class="@if (auth()->user()->can('add_product_attribute')) col-lg-7 @else col-lg-12 @endif">
            <div class="card">
            @include('admin.includes.alerts.success')
          @include('admin.includes.alerts.errors')
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ tran('Attributes') }}</h5>
                </div>
                <div class="card-body">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ tran('Name') }}</th>
                                <th>{{ tran('Values') }}</th>
                                <th class="text-right">{{ tran('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attributes as $key => $attribute)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $attribute->name}}</td>
                                    <td>
                                        @foreach ($attribute->attribute_values as $key => $value)
                                            <span
                                                class="badge badge-inline badge-md bg-soft-dark">{{ $value->value }}</span>
                                        @endforeach
                                    </td>
                                    <td class="text-right">
                                        @can('view_product_attribute_values')
                                            <a class="btn btn-light btn-sm btn-soft-primary"
                                                href="{{ route('attributes.show', $attribute->id) }}"
                                                title="{{ tran('Attribute values') }}">
                                                <i class="fa-solid fa-gear"></i>
                                            </a>
                                        @endcan
                                        <br>  <br>
                                        @can('edit_product_attribute')
                                            <a class="btn btn-light  btn-circle btn-sm"
                                                href="{{ route('attributes.edit',$attribute->id) }}"
                                                title="{{ tran('Edit') }}">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                        @endcan
                                        <br>  <br>
                                        @can('delete_product_attribute')
                                            <a href="#"
                                                class="btn btn-light  btn-circle btn-sm confirm-delete"
                                                data-href="{{ route('admin.attributes.delete', $attribute->id) }}"
                                                title="{{ tran('Delete') }}">
                                                <i class="fa-sharp fa-solid fa-trash"></i>
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                  
                  
                    <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        {{ $attributes->links() }}
                        </ul>
                        </nav>
              
               
                </div>
            </div>
        </div>
        @can('add_product_attribute')
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ tran('Add New Attribute') }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('attributes.store') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="name">{{ tran('Name') }}</label>
                                <input type="text" placeholder="{{ tran('Name') }}" id="name" name="name"
                                    class="form-control" required>
                            </div>
                            <div class="form-group mb-3 text-right">
                                <button type="submit" class="btn btn-primary">{{ tran('Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endcan
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@push('js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  @endpush