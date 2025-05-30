<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{  tran('INVOICE') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<style media="all">
        @page {
			margin: 0;
			padding:0;
		}
		body{
			/* font-size: 0.875rem; */
            font-family: '<?php echo  $font_family ?>';
            font-weight: normal;
            direction: <?php echo  $direction ?>;
            text-align: <?php echo  $text_align ?>;
			padding:0;
			margin:0; 
		}
		.gry-color *,
		.gry-color{
			color:#000;
		}
		table{
			width: 100%;
		}
		table th{
			font-weight: normal;
		}
		table.padding th{
			padding: .25rem .7rem;
		}
		table.padding td{
			padding: .25rem .7rem;
		}
		table.sm-padding td{
			padding: .1rem .7rem;
		}
		.border-bottom td,
		.border-bottom th{
			border-bottom:1px solid #eceff4;
		}
		.text-left{
			text-align:<?php echo  $text_align ?>;
		}
		.text-right{
			text-align:<?php echo  $not_text_align ?>;
		}
	</style>
</head>
<body>
<div>

		@php
			$logo = get_setting('header_logo');
		@endphp

		<div style="background: #eceff4;padding: 1rem;">
			<table>
				<tr>
					<td>
						@if($logo != null)
							<img src="{{ asset($logo) }}" height="30" style="display:inline-block;">
						@else
							<img src="{{ asset('assets/images/default.png') }}" height="30" style="display:inline-block;">
						@endif
					</td>
					<td  class="text-right strong">{{  tran('INVOICE') }}</td>
				</tr>
			</table>

			<table>
				<tr>
					<td class="strong">{{ get_setting('site_name') }}</td>
					<td class="text-right"></td>
				</tr>
				<tr>
					<td class="gry-color small">{{ get_setting('contact_address') }}</td>
					<td class="text-right"></td>
				</tr>

				<tr>
					<td class="gry-color small">{{  tran('Email') }}: {{ get_setting('contact_email') }}</td>
					<td class="text-right small"><span class="gry-color small">{{  tran('Order ID') }}:</span> <span class="strong">{{ $order->code }}</span></td>
				</tr>

				<tr>
					<td class="gry-color small">{{  tran('Phone') }}: {{ get_setting('contact_phone') }}</td>
					<td class="text-right small"><span class="gry-color small">{{  tran('Order Date') }}:</span> <span class="strong">{{ date('d-m-Y', $order->date) }}</span></td>

					<td class="text-right small"><span class="gry-color small">{{  tran('Order Time') }}:</span><span class="strong"> {{ date('h:i A', $order->date) }}</span></td>
				</tr>

				<tr>
					<td class="gry-color small"></td>
					<td class="text-right small">
                        <span class="gry-color small">
                            {{  tran('Payment method') }}:
                        </span> 
                        <span class="strong">
                            {{ tran(ucfirst(str_replace('_', ' ', $order->payment_type))) }}
                        </span>
                    </td>
				</tr>

			</table>
		</div>


		<div style="padding: 1rem;padding-bottom: 0">
            <table>
				@php
					$shipping_address = json_decode($order->shipping_address);
				@endphp
				<tr><td class="strong small gry-color">{{ tran('Bill to') }}:</td></tr>
				<tr><td class="strong">{{ $shipping_address->name }}</td></tr>
				<tr><td class="gry-color small">{{ $shipping_address->address }}, {{ $shipping_address->city }},  @if(isset(json_decode($order->shipping_address)->state)) {{ json_decode($order->shipping_address)->state }} - @endif {{ $shipping_address->postal_code }}, {{ $shipping_address->country }}</td></tr>
				<tr><td class="gry-color small">{{ tran('Email') }}: {{ $shipping_address->email }}</td></tr>
				<tr><td class="gry-color small">{{ tran('Phone') }}: {{ $shipping_address->phone }}</td></tr>
			</table>
		</div>


		<div style="padding: 1rem;font-size:15px;">
			<table class="padding text-left small border-bottom">
				<thead>
	                <tr class="gry-color" style="background: #eceff4;">
	                    <th width="250"  class="text-left">{{ tran('Product Name') }}</th>
						<th   class="text-left">{{ tran('Delivery Type') }}</th>
	                    <th   class="text-left">{{ tran('Qty') }}</th>
	                    <th   class="text-left">{{ tran('Unit Price') }}</th>
	                    <th   class="text-left">{{ tran('Tax') }}</th>
	                    <th   class="text-right">{{ tran('Total') }}</th>
	                </tr>
				</thead>
				<tbody class="strong">
	                @foreach ($order->orderDetails as $key => $orderDetail)
		                @if ($orderDetail->product != null)
							<tr class="">
								<td width="250">
                                    {{ $orderDetail->product->name }}
                                    <br>
                                  <a href="{{ route('product.details', $orderDetail->product->slug) }}" target="_blank">{{tran('View Product')}}</a>
                                   <br>
                                    @if($orderDetail->variation != null) ({{ $orderDetail->variation }}) @endif
                                    <br>
                                    <small>
                                        @php
                                            $product_stock = json_decode($orderDetail->product->stocks->first(), true);
                                        @endphp
                                        {{tran('SKU')}}: {{ $product_stock['sku'] }}
	                               </small>
                                </td>
								<td>
									@if ($order->shipping_type != null && $order->shipping_type == 'home_delivery')
										{{ tran('Home Delivery') }}
									@elseif ($order->shipping_type == 'pickup_point')
										@if ($order->pickup_point != null)
											{{ $order->pickup_point->getTranslation('name') }} ({{ tran('Pickip Point') }})
										@else
                                            {{ tran('Pickup Point') }}
										@endif
									@elseif ($order->shipping_type == 'carrier')
										@if ($order->carrier != null)
											{{ $order->carrier->name }} ({{ tran('Carrier') }})
											<br>
											{{ tran('Transit Time').' - '.$order->carrier->transit_time }}
										@else
											{{ tran('Carrier') }}
										@endif
									@endif
								</td>
								<td class="">{{ $orderDetail->quantity }}</td>
								<td class="currency">{{ single_price($orderDetail->price/$orderDetail->quantity) }}</td>
								<td class="currency">{{ single_price($orderDetail->tax/$orderDetail->quantity) }}</td>
			                    <td class="text-right currency">{{ single_price($orderDetail->price+$orderDetail->tax) }}</td>
							</tr>
		                @endif
					@endforeach
	            </tbody>
			</table>
		</div>


		<?php $shop = App\Models\Shop::where('user_id',$order->seller_id)->first();
		if($shop){
		?>
		
	   <div class="clearfix float-left">
                 <table class="table">
                    <tbody>
                         <tr>
                            <td>
                                <strong class="text-muted">{{ tran('Shop Name') }} : </strong> {{  $shop->name  }}
                            </td>
                          
                             
                        </tr>
                        
                        <tr>
                            <td>
                                <strong class="text-muted">{{ tran('Shop Address') }} :</strong>  {{  $shop->address  }}
                            </td>
                        
                             
                        </tr>
                        </table>
                      
                     
						
                 </div>

				 <div style="padding:0 1.5rem;">
	        <table class="text-right sm-padding small strong">
	        	<thead>
	        		<tr>
	        			<th width="60%"></th>
	        			<th width="40%"></th>
	        		</tr>
	        	</thead>
		        <tbody>
			        <tr>
			            <td class="text-left">

                            
			            </td>
			            <td>
					        <table class="text-right sm-padding small strong">
						        <tbody>
							        <tr>
							            <td class="gry-color text-left">{{ tran('Sub Total') }}</td>
							            <td class="currency">{{ single_price($order->orderDetails->sum('price')) }}</td>
							        </tr>
							        <tr>
							            <td class="gry-color text-left">{{ tran('Shipping Cost') }}</td>
							            <td class="currency">{{ single_price($order->orderDetails->sum('shipping_cost')) }}</td>
							        </tr>
							        <tr class="border-bottom">
							            <td class="gry-color text-left">{{ tran('Total Tax') }}</td>
							            <td class="currency">{{ single_price($order->orderDetails->sum('tax')) }}</td>
							        </tr>
				                    <tr class="border-bottom">
							            <td class="gry-color text-left">{{ tran('Coupon Discount') }}</td>
							            <td class="currency">{{ single_price($order->coupon_discount) }}</td>
							        </tr>
							        <tr>
							            <td class="text-left strong">{{ tran('Grand Total') }}</td>
							            <td class="currency">{{ single_price($order->grand_total) }}</td>
							        </tr>
						        </tbody>
						    </table>
			            </td>
			        </tr>
		        </tbody>
		    </table>
	    </div>
		<?php
		}

		?>
		</body>
</html>