@extends('layouts.admin')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3">{{ tran('Attribute Detail') }}</h1>
        </div>
    </div>

    <div class="row">
        <!-- Small table -->
        <div class="@if (auth()->user()->can('add_product_attribute_values')) col-lg-7 @else col-lg-12 @endif">
            <div class="card">
            @include('admin.includes.alerts.success')
          @include('admin.includes.alerts.errors')
                <div class="card-header">
                    <strong class="card-title">
                        {{ $attribute->name}}
                    </strong>
                </div>

                <div class="card-body">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ tran('Value') }}</th>
                                <th class="text-right">{{ tran('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_attribute_values as $key => $attribute_value)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        {{ $attribute_value->value }}
                                    </td>
                                    <td class="text-right">
                                        @can('edit_product_attribute_value')
                                            <a class="btn btn-soft-primary  btn-circle btn-sm"
                                                href="{{ route('edit-attribute-value', ['id' => $attribute_value->id]) }}"
                                                title="{{ tran('Edit') }}">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                        @endcan
                                        @can('delete_product_attribute_value')
                                            <a href="#"
                                                class="btn btn-soft-danger  btn-circle btn-sm confirm-delete"
                                                data-href="{{ route('destroy-attribute-value', $attribute_value->id) }}"
                                                title="{{ tran('Delete') }}">
                                                <i class="fa-sharp fa-solid fa-trash"></i>
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        @can('add_product_attribute_values')
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{ tran('Add New Attribute Value') }}</h5>
                    </div>
                    <div class="card-body">
                        <!-- Error Meassages -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('store-attribute-value') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="name">{{ tran('Attribute Name') }}</label>
                                <input type="hidden" name="attribute_id" value="{{ $attribute->id }}">
                                <input type="text" placeholder="{{ tran('Name') }}" name=""
                                    value="{{ $attribute->name }}"class="form-control" readonly>
                                <input type="hidden" name="attribute_type" value="{{ $attribute->type }}">
                            </div>
                            <div class="form-group mb-3">
                                <label for="name">{{ tran('Attribute Value') }}</label>
                                <input type="text" placeholder="{{ tran('Name') }}" id="value" name="value"
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
