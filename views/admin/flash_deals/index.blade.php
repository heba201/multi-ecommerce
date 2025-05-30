@extends('layouts.admin')
@section('content')

<div class="page-header">
            <h3 class="page-title">
              {{tran('Flash Deals')}}
              <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{tran('Flash Deals')}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{tran('Show All')}}</li>
              </ol>
            </nav>
            </h3>
          </div>

<div class="card">
@include('admin.includes.alerts.success')
@include('admin.includes.alerts.errors')
    <div class="card-header">
        <h5 class="mb-0 h6">{{tran('Flash Deals')}}</h5>
        <div class="pull-right clearfix">
            
        </div>
    </div>
    <div class="card-body">
 
        <table class="table" id="order-listing" >
            <thead>
                <tr>
                    <th data-breakpoints="lg">#</th>
                    <th>{{tran('Title')}}</th>
                    <th data-breakpoints="lg">{{ tran('Banner') }}</th>
                    <th data-breakpoints="lg">{{ tran('Start Date') }}</th>
                    <th data-breakpoints="lg">{{ tran('End Date') }}</th>
                    <th data-breakpoints="lg">{{ tran('Status') }}</th>
                    <th data-breakpoints="lg">{{ tran('Featured') }}</th>
                    <th  class="text-right" >{{tran('Options')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($flash_deals as $key => $flash_deal)
                    <tr>
                        <td>{{ ($key+1) + ($flash_deals->currentPage() - 1)*$flash_deals->perPage() }}</td>
                        <td>{{ $flash_deal->title }}</td>
                        <td>  @if($flash_deal->banner != null)<span class="avatar avatar-square avatar-xs"><img src="{{ asset('assets/images/flashdeals/'.$flash_deal->banner) }}" alt="banner"> </span>@endif</td>
                        <td>{{ date('d-m-Y H:i:s', $flash_deal->start_date) }}</td>
                        <td>{{ date('d-m-Y H:i:s', $flash_deal->end_date) }}</td>
                        <td>
							<label class="chk-switch chk-switch-success mb-0">
								<input onchange="update_flash_deal_status(this)" value="{{ $flash_deal->id }}" type="checkbox" <?php if($flash_deal->status == 1) echo "checked";?> >
								<span></span>
							</label>
						</td>
						<td>
							<label class="chk-switch chk-switch-success mb-0">
								<input 
                                @can('publish_flash_deal')  onchange="update_flash_deal_feature(this)"  @endcan
                                    value="{{ $flash_deal->id }}" type="checkbox" 
                                    <?php if($flash_deal->featured == 1) echo "checked";?>
                                    @cannot('publish_flash_deal') disabled @endcan
                                >
								<span></span> 
							</label>
						</td>
						<td  class="text-right">
                        @can('edit_flash_deal')
                                <a class="btn btn-light" style="padding:10px;"  href="{{route('flash_deals.edit', ['id'=>$flash_deal->id, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" title="{{ tran('Edit') }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                @endcan
                                @can('delete_flash_deal')
                                <a href="#" class="btn btn-light  confirm-delete" style="padding:10px;"  data-href="{{route('flash_deals.destroy', $flash_deal->id)}}" title="{{ tran('Delete') }}">
                                <i class="fa-sharp fa-solid fa-trash"></i>
                                </a>
                                @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="clearfix">
            <div class="pull-right">
                {{ $flash_deals->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
</div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@push('js')
    <script type="text/javascript">
        function update_flash_deal_status(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('{{ route('flash_deals.update_status') }}', {_token:'{{ csrf_token() }}', id:el.value, status:status}, function(data){
                if(data == 1){
                    location.reload();
                }
                else{
                    AIZ.plugins.notify('danger', '{{ tran('Something went wrong') }}');
                }
            });
        }
        function update_flash_deal_feature(el){
            if(el.checked){
                var featured = 1;
            }
            else{
                var featured = 0;
            }
            $.post('{{ route('flash_deals.update_featured') }}', {_token:'{{ csrf_token() }}', id:el.value, featured:featured}, function(data){
                if(data == 1){
                    location.reload();
                }
                else{
                    AIZ.plugins.notify('danger', '{{ tran('Something went wrong') }}');
                }
            });
        }
    </script>
@endpush
@section('modal')
    @include('modals.delete_modal')
@endsection