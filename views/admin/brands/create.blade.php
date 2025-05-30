@extends('layouts.admin')
@section('content')

<div class="page-header">
            <h3 class="page-title">
            {{tran('Brands')}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{tran('Brands')}}</a></li>
                <li class="breadcrumb-item active" aria-current="page"> {{tran('Add New Brand')}}</li>
                </ol>
            </nav>
            </h3>
            
          </div>
          @include('admin.includes.alerts.success')
          @include('admin.includes.alerts.errors')
      
          <div class="row">
    <div class="col-lg-12 mx-auto">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0 h6">{{ tran('Add New Brand') }}</h5>
				</div>
				<div class="card-body">
					<form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="form-group mb-3">
							<label for="name">{{tran('Name')}}</label>
							<input type="text" placeholder="{{tran('Name')}}" name="name" class="form-control" required>
						</div>
						<div class="form-group mb-3">
							<label for="name">{{tran('Logo')}} <small>({{ tran('120x80') }})</small></label>
							<div class="input-group" data-toggle="aizuploader" data-type="image">
								<input type="file" name="logo" class="selected-files">
								@error('logo')
								<span class="text-danger">{{$message}}</span>
								@enderror
							</div>
							<div class="file-preview box sm">
							</div>
						</div>
						<div class="form-group mb-3">
							<label for="name">{{tran('Meta Title')}}</label>
							<input type="text" class="form-control" name="meta_title" placeholder="{{tran('Meta Title')}}">
						</div>
						<div class="form-group mb-3">
							<label for="name">{{tran('Meta Description')}}</label>
							<textarea name="meta_description" rows="5" class="form-control"></textarea>
						</div>

						<div class="form-group  mb-3">
                <label for="name">{{tran('Top')}}</label>
                <label class="chk-switch chk-switch-success mb-0">
                    <input type="checkbox" id="check"  onclick="setchecked()" name="top">
                    <span></span>
                </label>
                </div>

						<div class="form-group mb-3 text-right">
							<button type="submit" class="btn btn-primary">{{tran('Save')}}</button>
						</div>
					</form>
				</div>
			</div>
		</div>
    </div>
	@push('js')
<script>
	setchecked();
function setchecked(){
if ( $("#check").is(':checked')) { 
        // This will only be fired if checkbox is not checked
        $("#check").val("1");
    }else   $("#check").val("0");
}
</script>
@endpush
    @endsection