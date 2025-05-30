<?php

namespace App\Http\Controllers\Site;

use App\Events\NewOrder;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Orderitem;
use App\Models\CombinedOrder;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Address;
use App\Models\Transaction;
use App\Models\Coupon;
use App\Models\Carrier;
use App\Models\CouponUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use Session;
use Auth;
class PaymentController extends Controller
{
    private $base_url;
    private $request_client;
    private $token;


  public function fatoorah(){
$token ='map7mmxHnVxraKXGDC3A9R-hJptVHKdEUflEC3wtz2Mrv80t5EEVMAybPbZwdIjTQy8dHwktUTxYFt8DsNr3sWtcjC36BqdRTbVWs3oQ9s_a9JhY1E5Xrtk5TdYNOBjDxa25HilsMRYBEiAZcGnt0jzMo-orvZSJ4EVNtK-NOMPUK9GQ5yrwclqCwAou0uERPnfrv8lm85AVROLOUmCklvgZ-2uAV7rGYmeqcToDNC-KV45_FPrI6P4X1B7R3dkj8vg9EJCKKKx7UGV2CVNNRH9D3RKAkskp2wLpTlBh0k9fWt1Xl0wKgvB3K9WjP8tBwaX3fkAsFvoVh_8EAZSovquUlDiaX-OQRi39V662jsSSIsNd9IL3MoUUvMTo4hyr-H-g2EEOBQjYuVy6fM5mD3VzBISndvJOtFuNh3xqxt9PJOshP00cwHTE3XjQjWsW2KoGhEtFdmgml7GLp3Ve1ndJ5LIGRWM7Cyj6dsY5Idks4h5JC-J5YphwD-6THn639vjQF2rNPp27fyIe9DwD5OAcr4EoSy_Nuof_Eq4xjYUhiTdwNnmgjUb4uu77PzjK54Mib7H0PJBK0hIJG9AlvIzsDQlU_VTMJrU2togZdGLWoDc06r8tuKZo75iX4vYGUrMT4OR_XVmR3hgKtdLLpBEFrGagGSgdt4enUv-sk_KgNjpC';
$basURL = 'https://apitest.myfatoorah.com';

          $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "$basURL/v2/ExecutePayment",
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\"PaymentMethodId\":\"2\",\"CustomerName\": \"Ahmed\",\"DisplayCurrencyIso\": \"KWD\", \"MobileCountryCode\":\"+965\",\"CustomerMobile\": \"92249038\",\"CustomerEmail\": \"aramadan@myfatoorah.com\",\"InvoiceValue\": 100,\"CallBackUrl\": \"https://google.com\",\"ErrorUrl\": \"https://google.com\",\"Language\": \"en\",\"CustomerReference\" :\"ref 1\",\"CustomerCivilId\":12345678,\"UserDefinedField\": \"Custom field\",\"ExpireDate\": \"\",\"CustomerAddress\" :{\"Block\":\"\",\"Street\":\"\",\"HouseBuildingNo\":\"\",\"Address\":\"\",\"AddressInstructions\":\"\"},\"InvoiceItems\": [{\"ItemName\": \"Product 01\",\"Quantity\": 1,\"UnitPrice\": 100}]}",
  CURLOPT_HTTPHEADER => array("Authorization: Bearer $token","Content-Type: application/json"),
));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
    return  "cURL Error #:" . $err;
} else {
return response() -> json($response);

}

 $json  = json_decode((string)$response, true);
 //echo "json  json: $json '<br />'";

$payment_url = $json["Data"]["PaymentURL"];

    # after getting the payment url call it as a post API and pass card info to it
    # if you saved the card info before you can pass the token for the api

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "$payment_url",
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\"paymentType\": \"card\",\"card\": {\"Number\":\"5123450000000008\",\"expiryMonth\":\"05\",\"expiryYear\":\"21\",\"securityCode\":\"100\"},\"saveToken\": false}",
  CURLOPT_HTTPHEADER => array("Authorization: Bearer $token","Content-Type: application/json"),
));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response2 = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
   return  "cURL Error #:" . $err;
} else {
   // return 'dfdfdf';
  return response() -> json($response2);
}

}

    public function __construct()
    {
    }

    public function getPayments()
    {
        $carts = Cart::where('user_id', Auth::user()->id)->get();
        

        return view('front.cart.shipping_info', compact('carts'));
    }

    /**
     * @param Request $request
     */
    public function processPayment(Request $request)
    {
        $error = '';
        //best practice as we do sperate validation on request form file
        $validator = Validator::make($request->all(), [
            'ccNum' => 'required',
            'ccExp' => 'required',
            'ccCvv' => 'required|numeric',
            'amount' => 'required|numeric|min:100',
        ]);
        if ($validator->fails()) {
            $error = tran('Please check if you have filled in the form correctly. Minimum order amount is PHP 100.');
        }
        $ccNum = str_replace(' ', '', $request->ccNum);
        $ccExp = $request->ccExp;
        $ccCvv = $request->ccCvv;
        $amount = $request->amount;
        $customerName = auth()->user()->name;
        $customerEmail = 'demo@gmail.com';
        $phone = substr(auth()->user()->mobile, 4);
        $ccExp = (explode('/', $ccExp));
        $ccMon = $ccExp[0];
        $ccYear = $ccExp[1];
        $customerMobile = strlen($phone) <= 11 ? $phone : '123456';
        $data['Language'] = 'en';
        $PaymentMethodId = $request->PaymentMethodId;
       // $token = $this->token;
       $token="rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL";
       // $basURL = $this->base_url;
       $basURL = "https://apitest.myfatoorah.com";
       $postFields = [
        //Fill required data
        'NotificationOption' => 'Lnk', //'SMS', 'EML', or 'ALL'
        'InvoiceValue'       => '50',
        'CustomerName'       => 'fname lname',
    ];
    
        $curl = curl_init();
        // you can use laravel http or Guzzl and you my create an object to encode that oject direct on requrest
        curl_setopt_array($curl, array(
            CURLOPT_URL => "$basURL/v2/SendPayment",
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($postFields),
            CURLOPT_HTTPHEADER => array("Authorization: Bearer $token", "Content-Type: application/json"),
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return [
                'payment_success' => false,
                'status' => 'faild',
                'error' => $err
            ];
        }
    //  return $response;
        $json = json_decode((string)$response, true);
        //echo "json  json: $json '<br />'";
/////////////////////////////////////////////////////////
// {"IsSuccess":true,"Message":"Invoice Created Successfully!","ValidationErrors":null,"Data":{"InvoiceId":2332970,"IsDirectPayment":false,"PaymentURL":"https://demo.MyFatoorah.com/En/KWT/PayInvoice/Checkout?invoiceKey=01072233297041-cd96461f&paymentGatewayId=22","CustomerReference":"ref 1","UserDefinedField":"Custom field","RecurringId":""}}
// return $json["Data"]["PaymentURL"] ;
/////////////////////////////////////////////////////////////////
        $payment_url = $json["Data"]["InvoiceURL"];
        // return $payment_url;
        // https://demo.MyFatoorah.com/En/KWT/PayInvoice/Checkout?invoiceKey=01072233298141-166c65e3&paymentGatewayId=22
        ////////////////////////////////////////////////////////////////
        $card = new \stdClass();
        $card->Number = $ccNum;
        $card->expiryMonth = trim($ccMon);
        $card->expiryYear = trim($ccYear);
        $card->securityCode = trim($ccCvv);
        $card_data = json_encode($card);

        $postFields = [
            //Fill required data
            'NotificationOption' => 'Lnk', //'SMS', 'EML', or 'ALL'
            'InvoiceValue'       => $amount,
            'CustomerName'       => 'fname lname'  
        ];
        //   return $card_data;
        $curl = curl_init();
        curl_setopt_array($curl, array(
             CURLOPT_URL => $payment_url,
            // CURLOPT_CUSTOMREQUEST => "POST",
             CURLOPT_POSTFIELDS => json_encode($postFields),
            // CURLOPT_HTTPHEADER => array("Authorization: Bearer $token", "Content-Type: application/json"),
            CURLOPT_CUSTOMREQUEST  => 'POST',
            // CURLOPT_POSTFIELDS     => json_encode($postFields),
            CURLOPT_HTTPHEADER     => array("Authorization: Bearer $token", 'Content-Type: application/json'),
            CURLOPT_RETURNTRANSFER => true,
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $err = curl_error($curl);
       
        curl_close($curl);

        if ($err) {
            return [
                'paymemt_success' => false,
                'status' => 'faild',
                'error' => $err
            ];
        }
        $json = json_decode((string)$response, true);
       if(!empty($json)){
        $PaymentId = $json["Data"]["PaymentId"];
       }else{
        $PaymentId=time();
       }
        try {
            DB::beginTransaction();
            // if success payment save order and send realtime notification to admin
            $order = $this->saveOrder($amount, $PaymentMethodId);  // your task is  . add products with options to order to preview on admin
            $this->saveTransaction($order, $PaymentId);
            $this->SaveOrderItems($order);
            DB::commit();

            //fire event on order complete success for realtime notification
            event(new NewOrder($order));

        } catch (\Exception $ex) {
            DB::rollBack();
            return $ex;
        }
        // replace return statment with message that tell the user that the payment successes
        // return [
        //     'payment_success' => true,
        //     'token' => $PaymentId,
        //     'data' => $json,
        //     'status' => 'succeeded',
        // ];
        return redirect()->route('home');
    }

    private function saveOrder($amount, $PaymentMethodId)
    {
        return Order::create([
            'customer_id' => auth()->id(),
            'customer_phone' => auth()->user()->mobile,
            'customer_name' => auth()->user()->name,
            'total' => $amount,
            'locale' => 'en',
            'payment_method' => $PaymentMethodId,  // you can use enumeration here as we use before for best practices for constants.
            'status' => Order::status_message,
            'payment_status' => Order::PAID,
        ]);

    }

    private function saveTransaction(Order $order, $PaymentId)
    {
        Transaction::create([
            'order_id' => $order->id,
            'transaction_id' => $PaymentId,
            'payment_method' => $order->payment_method,
        ]);
    }
           
    private function SaveOrderItems(Order $order){
        $cart = session()->get('cart', []);
        if(count($cart) > 0){
        foreach ($cart as $key => $value) {
            $product=Product::find($key);
            if($product){
                $calprice= $product -> special_price ?? $product -> price;
                $quantity=$value;
                Orderitem::create([
                    'order_id'=>$order->id, 
                    'product_id'=>$key,
                     'quantity'=>$value,
                      'price'=>$calprice
                ]);
            }
            session()->put('cart', []);
        }
        }
    }


    public function apply_coupon_code(Request $request)
    {
        $coupon = Coupon::where('code', $request->code)->first();
       // return $request->code;
       
        $response_message = array();
        if ($coupon != null) {
            if (strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date) {
                if (CouponUsage::where('user_id', Auth::user()->id)->where('coupon_id', $coupon->id)->first() == null) {
                    $coupon_details = json_decode($coupon->details);
                    $carts = Cart::where('user_id', Auth::user()->id)
                                    ->where('owner_id', $coupon->user_id)
                                    ->get();
                                    
                    $coupon_discount = 0;
                    if ($coupon->type == 'cart_base') {
                        $subtotal = 0;
                        $tax = 0;
                        $shipping = 0;
                        foreach ($carts as $key => $cartItem) { 
                            $product = Product::find($cartItem['product_id']);
                            $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                            $tax += cart_product_tax($cartItem, $product,false) * $cartItem['quantity'];
                            $shipping += $cartItem['shipping_cost'];
                        }
                        $sum = $subtotal + $tax + $shipping;
                        if ($sum >= $coupon_details->min_buy) {
                            if ($coupon->discount_type == 'percent') {
                                $coupon_discount = ($sum * $coupon->discount) / 100;
                                if ($coupon_discount > $coupon_details->max_discount) {
                                    $coupon_discount = $coupon_details->max_discount;
                                }
                            } elseif ($coupon->discount_type == 'amount') {
                                $coupon_discount = $coupon->discount;
                            }

                        }
                     
                    } elseif ($coupon->type == 'product_base') {
                        foreach ($carts as $key => $cartItem) { 
                            $product = Product::find($cartItem['product_id']);
                            foreach ($coupon_details as $key => $coupon_detail) {
                                if ($coupon_detail->product_id == $cartItem['product_id']) {
                                    if ($coupon->discount_type == 'percent') {
                                        $coupon_discount += (cart_product_price($cartItem, $product, false, false) * $coupon->discount / 100) * $cartItem['quantity'];
                                    } elseif ($coupon->discount_type == 'amount') {
                                        $coupon_discount += $coupon->discount * $cartItem['quantity'];
                                    }
                                }
                            }
                        }
                    }

                    if($coupon_discount > 0){
                        Cart::where('user_id', Auth::user()->id)
                            ->where('owner_id', $coupon->user_id)
                            ->update(
                                [
                                    'discount' => $coupon_discount / count($carts),
                                    'coupon_code' => $request->code,
                                    'coupon_applied' => 1
                                ]
                            );
                        $response_message['response'] = tran('success');
                        $response_message['message'] = tran('Coupon has been applied');
                    }
                    else{
                        $response_message['response'] = 'warning';
                        $response_message['message'] = tran('This coupon is not applicable to your cart products!');
                    }
                    
                } else {
                    $response_message['response'] = 'warning';
                    $response_message['message'] = tran('You already used this coupon!');
                }
            } else {
                $response_message['response'] = 'error';
                $response_message['message'] = tran('Coupon expired!');
            }
        } else {
            $response_message['response'] =  'error'; 
            $response_message['message'] =  tran('Invalid coupon!');
        }
        $carts = Cart::where('user_id', Auth::user()->id)
                ->get();
        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();
        return  $response_message;
    }

    public function remove_coupon_code(Request $request)
    {
        $response_message = array();
        Cart::where('user_id', Auth::user()->id)
                ->update(
                        [
                            'discount' => 0.00,
                            'coupon_code' => '',
                            'coupon_applied' => 0
                        ]
        );
        $coupon = Coupon::where('code', $request->code)->first();
        $carts = Cart::where('user_id', Auth::user()->id)
                ->get();
        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();
        $response_message['response'] = 'Success';
        $response_message['message'] = tran('Coupon is removed successfully');
        return  $response_message;
    }


    public function store_shipping_info(Request $request)
    {
        if ($request->address_id == null) {
           
            return back()->with(["warning"=>tran('Please add shipping address')]);
        }
        $carts = Cart::where('user_id', Auth::user()->id)->get();
        if($carts->isEmpty()) {
            
            return redirect()->route('home')->with(["warning"=>tran('Your cart is empty')]);
        }

        foreach ($carts as $key => $cartItem) {
            $cartItem->address_id = $request->address_id;
            $cartItem->save();
        }
        $carrier_list = array();
        if(get_setting('shipping_type') == 'carrier_wise_shipping'){
            $zone = \App\Models\Country::where('id',$carts[0]['address']['country_id'])->first()->zone_id;

            $carrier_query = Carrier::query();
            $carrier_query->whereIn('id',function ($query) use ($zone) {
                $query->select('carrier_id')->from('carrier_range_prices')
                ->where('zone_id', $zone);
            })->orWhere('free_shipping', 1);
            $carrier_list = $carrier_query->get();
        }    
     return view('front.cart.delivery_info',compact('carts','carrier_list'));
    }


    public function store_delivery_info(Request $request)
    {
        $carts = Cart::where('user_id', Auth::user()->id)
                ->get();
        if($carts->isEmpty()) {
            return redirect()->route('home')->with(['error'=>'Your cart is empty']);
        }

        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();
        $total = 0;
        $tax = 0;
        $shipping = 0;
        $subtotal = 0;
        if ($carts && count($carts) > 0) {
            foreach ($carts as $key => $cartItem) {
                $product = Product::find($cartItem['product_id']);
                $tax += cart_product_tax($cartItem, $product,false) * $cartItem['quantity'];
                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];

                if(get_setting('shipping_type') != 'carrier_wise_shipping' || $request['shipping_type_' . $product->user_id] == 'pickup_point'){
                    if ($request['shipping_type_' . $product->user_id] == 'pickup_point') {
                        $cartItem['shipping_type'] = 'pickup_point';
                        $cartItem['pickup_point'] = $request['pickup_point_id_' . $product->user_id];
                    } else {
                        $cartItem['shipping_type'] = 'home_delivery';
                    }
                    $cartItem['shipping_cost'] = 0;
                    if ($cartItem['shipping_type'] == 'home_delivery') {
                        $cartItem['shipping_cost'] = getShippingCost($carts, $key);
                        $arr[]= getShippingCost($carts, $key);
                        $arr[]= $key;
                    }
                }
                else{
                    $cartItem['shipping_type'] = 'carrier';
                    $cartItem['carrier_id'] = $request['carrier_id_' . $product->user_id];
                    $cartItem['shipping_cost'] = getShippingCost($carts, $key, $cartItem['carrier_id']);
                    $arr[]=getShippingCost($carts, $key, $cartItem['carrier_id']);
                    $arr[]="2";
                }
             
                $shipping += $cartItem['shipping_cost'];
                $cartItem->save();
            }
            $total = $subtotal + $tax + $shipping;
            
        } else {
           
            return redirect()->route('home')->with(["warning"=>tran('Your Cart was empty')]);
        }
        return view('front.cart.payments',compact('carts'));
    }


    public function checkout(Request $request)
    {
    //  $this->store_shipping_info($request);
     //$this->store_delivery_info($request);
        // Minumum order amount check
        if(get_setting('minimum_order_amount_check') == 1){
            $subtotal = 0;
            foreach (Cart::where('user_id', Auth::user()->id)->get() as $key => $cartItem){ 
                $product = Product::find($cartItem['product_id']);
                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
            }
            if ($subtotal < get_setting('minimum_order_amount')) {
                
                return redirect()->route('home')->with(["error"=>tran('You order amount is less then the minimum order amount')]);
            }
        }
        // Minumum order amount check end
        if ($request->payment_option != null) {
          (new OrderController)->store($request);
            $request->session()->put('payment_type', 'cart_payment');
            $data['combined_order_id'] = $request->session()->get('combined_order_id');
            $request->session()->put('payment_data', $data);
            if ($request->session()->get('combined_order_id') != null) {
                // If block for Online payment, wallet and cash on delivery. Else block for Offline payment
                $decorator = __NAMESPACE__ . '\\Payment\\' . str_replace(' ', '', ucwords(str_replace('_', ' ', $request->payment_option))) . "Controller";
             // return  $decorator .'/' . class_exists($decorator);
                if (class_exists($decorator)) {
                    return (new $decorator)->pay($request);
                }
                else {
                    $combined_order = CombinedOrder::findOrFail($request->session()->get('combined_order_id'));
                    $manual_payment_data = array(
                        'name'   => $request->payment_option,
                        'amount' => $combined_order->grand_total,
                        'trx_id' => $request->trx_id,
                        'photo'  => $request->photo
                    );
                    foreach ($combined_order->orders as $order) {
                        $order->manual_payment = 1;
                        $order->manual_payment_data = json_encode($manual_payment_data);
                        $order->save();
                    } 
                 return redirect()->route('payment.order_confirmed')->with(["success"=>tran('Your order has been placed successfully. Please submit payment information from purchase history')]);
                }
            }
        } else {
           return back()->with(["warning"=>tran('Select Payment Option.')]);
        }
    }

    public function order_confirmed()
    {
        $combined_order = CombinedOrder::findOrFail(Session::get('combined_order_id'));
        Cart::where('user_id', $combined_order->user_id)
                ->delete();
        return view('front.cart.order_confirmed', compact('combined_order'));
    }


    //redirects to this method after a successfull checkout
    public function checkout_done($combined_order_id, $payment)
    {
        $combined_order = CombinedOrder::findOrFail($combined_order_id);
        foreach ($combined_order->orders as $key => $order) {
            $order = Order::findOrFail($order->id);
            $order->payment_status = 'paid';
            $order->payment_details = $payment;
            $order->save();
            calculateCommissionAffilationClubPoint($order);
        }
        Session::put('combined_order_id', $combined_order_id);
        return redirect()->route('payment.order_confirmed');
    }
}
