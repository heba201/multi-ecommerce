<?php
namespace App\Http\Controllers\Site\Payment;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerPackageController;
use App\Http\Controllers\SellerPackageController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\Site\PaymentController;
use Illuminate\Http\Request;
use App\Models\CombinedOrder;
use App\Models\CustomerPackage;
use App\Models\SellerPackage;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use Session;
use Redirect;
use Artisan;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductStock;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
class PaypalController extends Controller
{

    public function pay()
    {
        if(Session::has('payment_type')) {
            if(Session::get('payment_type') == 'cart_payment') {
                $combined_order = CombinedOrder::findOrFail(Session::get('combined_order_id'));
                $amountv = $combined_order->grand_total;
            }
            elseif (Session::get('payment_type') == 'wallet_payment') {
                $amountv = Session::get('payment_data')['amount'];
            }
            elseif (Session::get('payment_type') == 'customer_package_payment') {
                $customer_package = CustomerPackage::findOrFail(Session::get('payment_data')['customer_package_id']);
                $amountv = $customer_package->amount;
            }
            elseif (Session::get('payment_type') == 'seller_package_payment') {
                $seller_package = SellerPackage::findOrFail(Session::get('payment_data')['seller_package_id']);
                $amountv = $seller_package->amount;
            }
        }
    
      try {
        Artisan::call('config:cache');
       
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(env('PAYPAL_CLIENT_ID'),env('PAYPAL_CLIENT_SECRET'))
        );
         $order_currency=\App\Models\Currency::findOrFail(get_setting('system_default_currency'))->code;
           // $order_currency="USD"; // in paypal account add more than currencies -- checked  in live 
           $payer = new Payer();
             $payer->setPaymentMethod("paypal");
             $amount = new Amount();
             $amount->setCurrency($order_currency)
                 ->setTotal(number_format($amountv, 2, '.', ''));
             $transaction = new Transaction();
             $transaction->setAmount($amount)
                 ->setDescription("Buy products")
                 ->setInvoiceNumber(uniqid());
       
           //   $baseUrl = $this->getBaseUrl();
       
             $redirectUrls = new RedirectUrls();
             $redirectUrls->setReturnUrl(route('payment.done',$combined_order->id))
                 ->setCancelUrl(route('payment.cancel',$combined_order->id));
             $payment = new Payment();
             $payment->setIntent("order")
                 ->setPayer($payer)
                 ->setRedirectUrls($redirectUrls)
                 ->setTransactions(array($transaction));
             //For Sample Purposes Only.
             $request = clone $payment;
          $paymentdetail = $payment->create($apiContext);
          $approvalUrl = $payment->getApprovalLink();
          return Redirect::to($approvalUrl);  
      } catch (\PayPal\Exception\PayPalConnectionException $ex) {
       
        // Curse and humiliate the user for cancelling this most sacred payment (yours)
      //return Session::get('combined_order_id').'mm'; 
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

    public function getCancel(Request $request,$orderid)
    {
        
        // Curse and humiliate the user for cancelling this most sacred payment (yours)
        $combined_order_data=CombinedOrder::findOrFail($orderid);
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
        $request->session()->forget('order_id');
        $request->session()->forget('payment_data');
    	return redirect()->route('home')->with(['success'=>tran('Payment cancelled')]);
    }

    public function getDone(Request $request,$orderid)
    {
        try {
            Artisan::call('config:cache');
            $paymentId = $_GET['paymentId'];
            $apiContext = new \PayPal\Rest\ApiContext(
                new \PayPal\Auth\OAuthTokenCredential(env('PAYPAL_CLIENT_ID'),env('PAYPAL_CLIENT_SECRET'))
            );
            $payment = \PayPal\Api\Payment::get($paymentId, $apiContext);
            // If call returns body in response, you can get the deserialized version from the result attribute of the response
        // return $paymentId;
            //return $payment;
            if($request->session()->has('payment_type')){
                if($request->session()->get('payment_type') == 'cart_payment'){
                    return (new PaymentController)->checkout_done($orderid, json_encode($payment));
                }
            }
        }catch (\Exception $ex) {
            return redirect()->route('home')->with(['error'=>tran('Something went wrong,please try again later')]);
        }
    }
}
