@extends('layouts.admin')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ tran('Attribute Information') }}</h5>
    </div>

    <div class="col-lg-8 mx-auto">
        <div class="card">
        @include('admin.includes.alerts.success')
          @include('admin.includes.alerts.errors')
            <div class="card-body p-0">
                <form class="p-4" action="{{ route('attributes.update', $attribute->id) }}" method="POST">
                    <input name="_method" type="hidden" value="PATCH">
                    <input type="hidden" name="lang" value="{{ $lang }}">
                    @csrf
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="name">{{ tran('Name') }}</label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{ tran('Name') }}" id="name" name="name"
                                class="form-control" required value="{{ $attribute->name }}">
                        </div>
                    </div>
                    
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{ tran('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
