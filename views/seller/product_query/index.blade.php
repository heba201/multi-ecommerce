@extends('seller.layouts.app')

@section('content')
<div class="aiz-titlebar mt-2 mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ tran('Product Queries') }}</h1>
        </div>
      </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ tran('Product Queries') }}</h5>
        </div>
        <div class="card-body">
            <table class="table mb-0" id="order-listing" cellspacing="0" width="100%">
                <thead>
                    <tr>
                     
                        <th>{{ tran('User Name') }}</th>
                        <th>{{ tran('Product Name') }}</th>
                        <th data-breakpoints="lg">{{ tran('Question') }}</th>
                        <th data-breakpoints="lg">{{ tran('Reply') }}</th>
                        <th>{{ tran('status') }}</th>
                        <th class="text-right">{{ tran('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($queries as $key => $query)
                        <tr>
                            
                            <td>{{ tran($query->user->name) }}</td>
                            <td>{{ tran($query->product->name) }}</td>
                            <td>{{ tran(Str::limit($query->question, 100)) }}</td>
                            <td>{{ tran(Str::limit($query->reply, 100)) }}</td>
                            <td>
                                <span
                                    class="badge badge-inline {{ $query->reply == null ? 'badge-warning' : 'badge-success' }}">
                                    {{ $query->reply == null ? tran('Not Replied') : tran('Replied') }}
                                </span>
                            </td>
                            <td class="text-right">
                                <a class="btn btn-light btn-sm"  style="padding:10px;"
                                    href="{{ route('seller.product_query.show', encrypt($query->id)) }}"
                                    title="{{ tran('View') }}">
                                    <i class="fa fa-eye text-primary"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
          
        </div>
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
