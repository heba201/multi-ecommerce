<style>
    .rate {
    float: left;
    height: 46px;
    padding: 0 10px;
}
.rate:not(:checked) > input {
    position:absolute;
    top:-9999px;
}
.rate:not(:checked) > label {
    float:right;
    width:1em;
    overflow:hidden;
    white-space:nowrap;
    cursor:pointer;
    font-size:30px;
    color:#ccc;
}
.rate:not(:checked) > label:before {
    content: '★ ';
}
.rate > input:checked ~ label {
    color: #ffc700;    
}
.rate:not(:checked) > label:hover,
.rate:not(:checked) > label:hover ~ label {
    color: #deb217;  
}
.rate > input:checked + label:hover,
.rate > input:checked + label:hover ~ label,
.rate > input:checked ~ label:hover,
.rate > input:checked ~ label:hover ~ label,
.rate > label:hover ~ input:checked ~ label {
    color: #c59b08;
}

</style>
<div class="modal-header">
    <h5 class="modal-title h6">{{tran('Review')}}</h5>
    <button type="button" class="close" data-bs-dismiss="modal">
        x
    </button>
</div>

@if($review == null)
    <!-- Add new review -->
    <form action="{{ route('reviews.store') }}" method="POST" >
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <div class="modal-body">
            <div class="form-group">
                <label class="opacity-60">{{ tran('Product')}}</label>
                <p>{{ $product->name}}</p>
            </div>
            <!-- Rating -->
            <div class="form-group">
                <label class="opacity-60">{{ tran('Rating')}}</label>
                <div class="rating rating-input">
                <div class="rate">
                <input type="radio" id="star5" name="rating" value="5" required/>
                <label for="star5" title="text">5 stars</label>
                <input type="radio" id="star4" name="rating" value="4" />
                <label for="star4" title="text">4 stars</label>
                <input type="radio" id="star3" name="rating" value="3" />
                <label for="star3" title="text">3 stars</label>
                <input type="radio" id="star2" name="rating" value="2" />
                <label for="star2" title="text">2 stars</label>
                <input type="radio" id="star1" name="rating" value="1" />
                <label for="star1" title="text">1 star</label>
                </div>
                </div>
            </div>
            <br>
            <!-- Comment -->
            <div class="form-group">
                <label class="opacity-60">{{ tran('Comment')}}</label>
                <textarea class="form-control rounded-0" rows="4" name="comment" placeholder="{{ tran('Your review')}}" required></textarea>
            </div>
            <!-- Review Images -->
           
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-secondary rounded-0" data-bs-dismiss="modal">{{tran('Cancel')}}</button>
            <button type="submit" class="btn btn-sm btn-primary rounded-0">{{tran('Submit Review')}}</button>
        </div>
    </form>
@else
    <!-- Review -->
    <li class="media list-group-item d-flex">
        <div class="media-body text-left">
            <!-- Rating -->
            <div class="form-group">
                <label class="opacity-60">{{ tran('Rating')}}</label>
                <p class="rating rating-sm">
                    @for ($i=0; $i < $review->rating; $i++)
                    <img src="{{asset('assets/imgs/template/icons/star.svg')}}">
                    @endfor
                    @for ($i=0; $i < 5-$review->rating; $i++)
                    <img src="{{asset('assets/imgs/template/icons/star-gray.svg')}}">
                    @endfor
                </p>
            </div>
            <!-- Comment -->
            <div class="form-group">
                <label class="opacity-60">{{ tran('Comment')}}</label>
                <p class="comment-text">
                    {{ $review->comment }}
                </p>
            </div>
            <!-- Review Images -->
        
        </div>
    </li>
@endif

