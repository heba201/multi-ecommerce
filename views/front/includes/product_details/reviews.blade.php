<div class="py-3 reviews-area">
    <ul class="list-group list-group-flush">
        @foreach ($reviews as $key => $review)
            @if ($review->user != null)
                <li class="media list-group-item d-flex px-3 px-md-4 border-0">
                    <!-- Review User Image -->
                    <span class="avatar avatar-md mr-3">
                        <img class="lazyload"
                         
                            src="{{ $review->user->avatar_original !='' ? asset('assets/images/users/'.$review->user->avatar_original) : asset('assets/images/users/avatar.png') }}"
                       >
                    </span>
                    <div class="media-body text-left">
                        <!-- Review User Name -->
                        <h3 class="fs-15 fw-600 mb-0">{{ $review->user->name }}
                        </h3>
                        <!-- Review Date -->
                        <div class="opacity-60 mb-1">
                            {{ date('d-m-Y', strtotime($review->created_at)) }}
                        </div>
                        <!-- Review ratting -->
                        <span class="rating rating-mr-1">
                            @for ($i = 0; $i < $review->rating; $i++)
                               
                                <img src="{{asset('assets/imgs/template/icons/star.svg')}}">
                            @endfor
                            @for ($i = 0; $i < 5 - $review->rating; $i++)
                               
                                <img src="{{asset('assets/imgs/template/icons/star-gray.svg')}}">
                            @endfor
                        </span>
                        <!-- Review Comment -->
                        <p class="comment-text mt-2 fs-14">
                            {{ $review->comment }}
                        </p>
                        <!-- Review Images -->
                     
                        <!-- Variation -->
                        @php
                            $OrderDetail = \App\Models\OrderDetail::with(['order' => function ($q) use($review) {
                                                $q->where('user_id', $review->user_id);
                                            }])->where('product_id', $review->product_id)->where('delivery_status', 'delivered')->first();
                        @endphp
                        @if ($OrderDetail && $OrderDetail->variation)
                            <small class="text-secondary fs-12">{{ tran('Variation :') }} {{ $OrderDetail->variation }}</small>
                        @endif
                    </div>
                </li>
            @endif
        @endforeach
    </ul>

    @if (count($reviews) <= 0)
        <div class="text-center fs-18 opacity-70">
            {{ tran('There have been no reviews for this product yet.') }}
        </div>
    @endif
    
    <!-- Pagination -->
    <div class="pagination product-reviews-pagination py-2 px-4 d-flex justify-content-end">
        {{ $reviews->links() }}
    </div>
</div>
