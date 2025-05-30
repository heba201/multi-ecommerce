@extends('layouts.admin')
@section('content')

<div class="page-header">
            <h3 class="page-title">
            {{tran('Brands')}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{tran('Brands')}}</a></li>
                <li class="breadcrumb-item active" aria-current="page"> {{tran('Edit Brands')}} </li>
                </ol>
            </nav>
            </h3>
            
          </div>
          @include('admin.includes.alerts.success')
          @include('admin.includes.alerts.errors')
          <div class="col-lg-12 mx-auto">
    <div class="card">
        <div class="card-body p-0">
          
            <form class="p-4" action="{{ route('admin.brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
               
                @csrf
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{tran('Name')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{tran('Name')}}" id="name" name="name" value="{{ $brand->name }}" class="form-control" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label" for="signinSrEmail">{{tran('Logo')}} <small>({{ tran('120x80') }})</small></label>
                    <div class="col-md-9">
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                           
                            <input type="file" name="logo" value="{{$brand->logo}}" class="selected-files">
                            @error('logo')
								<span class="text-danger">{{$message}}</span>
								@enderror
                        </div>
                        <div class="file-preview box sm">
                       @if(count(explode("brands/",$brand->logo)) > 1)<img src="{{$brand->logo}}" width="100" height="100">@endif
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label">{{tran('Meta Title')}}</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="meta_title" value="{{ $brand->meta_title }}" placeholder="{{tran('Meta Title')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label">{{tran('Meta Description')}}</label>
                    <div class="col-sm-9">
                        <textarea name="meta_description" rows="8" class="form-control">{{ $brand->meta_description }}</textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-3 col-from-label" for="name">{{tran('Slug')}}</label>
                    <div class="col-sm-9">
                        <input type="text" placeholder="{{tran('Slug')}}" id="slug" name="slug" value="{{ $brand->slug }}" class="form-control">
                    </div>
                </div>

                <div class="form-group row">
                <label class="col-sm-3 col-from-label" for="name">{{tran('Top')}}</label>
                <label class="chk-switch chk-switch-success mb-0">
                    <input type="checkbox" id="check"  onclick="setchecked()" name="top" <?php if( $brand->top  == 1) echo "checked";?> value="{{$brand->top}}">
                    <span></span>
                </label>
                </div>

                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{tran('Save')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('js')
<script>
function setchecked(){
if ( $("#check").is(':checked')) { 
        // This will only be fired if checkbox is not checked
        $("#check").val("1");
    }else   $("#check").val("0");
}
</script>
@endpush
            @endsection