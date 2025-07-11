@if(count($product_ids) > 0)
<table class="table table-bordered">
    <thead>
      <tr>
        <td width="50%">
            <span>{{tran('Product')}}</span>
        </td>
        <td data-breakpoints="lg" width="20%">
            <span>{{tran('Base Price')}}</span>
        </td>
        <td data-breakpoints="lg" width="20%">
            <span>{{tran('Discount')}}</span>
        </td>
        <td data-breakpoints="lg" width="10%">
            <span>{{tran('Discount Type')}}</span>
        </td>
      </tr>
    </thead>
    <tbody>
        @foreach ($product_ids as $key => $id)
            @php
              $product = \App\Models\Product::findOrFail($id);
              $flash_deal_product = \App\Models\FlashDealProduct::where('flash_deal_id', $flash_deal_id)->where('product_id', $product->id)->first();
            @endphp
            <tr>
                <td>
                  <div class="form-group row">
                      <div class="col-auto">
                          <img src="{{ asset('assets/images/products/'.$product->thumbnail_img)}}" class="size-60px img-fit" >
                      </div>
                      <div class="col">
                          <span>{{  $product->name  }}</span>
                      </div>
                  </div>
                </td>
                <td>
                    <span>{{ $product->unit_price }}</span>
                </td>
                <td>
                    <input type="number" lang="en" name="discount_{{ $id }}" value="{{ $product->discount }}" min="0" step="1" class="form-control" required>
                </td>
                <td>
                    <select class="aiz-selectpicker" name="discount_type_{{ $id }}">
                        <option value="amount" <?php if($product->discount_type == 'amount') echo "selected";?> >{{ tran('Flat') }}</option>
                        <option value="percent" <?php if($product->discount_type == 'percent') echo "selected";?> >{{ tran('Percent') }}</option>
                    </select>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endif
