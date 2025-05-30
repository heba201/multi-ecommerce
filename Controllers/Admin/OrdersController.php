<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Orderitem;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Shop;
use App\Models\Cart;
use App\Models\Address;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\OrderDetail;
use App\Models\CouponUsage;
use App\Models\Coupon;
use App\Models\User;
use App\Models\CombinedOrder;
use App\Models\SmsTemplate;
use Auth;
use Mail;
use App\Mail\InvoiceEmailManager;
use App\Utility\NotificationUtility;
use CoreComponentRepository;
use App\Utility\SmsUtility;
use Illuminate\Support\Facades\Route;
class OrdersController extends Controller
{
    
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:view_all_orders|view_inhouse_orders|view_seller_orders|view_pickup_point_orders'])->only('all_orders');
        $this->middleware(['permission:view_order_details'])->only('show');
        $this->middleware(['permission:delete_order'])->only('destroy','bulk_order_delete');
    }
    public function index()
    {
        $orders = Order::with('user')->orderBy('id', 'DESC')->get();
       
        return view('admin.orders.index', compact('orders'));
    }

    // public function show($id)
    // {
    //     $order = Order::find($id);
    //     if(!$order)
    //     return redirect()->route('admin.orders')->with(['error' => 'This order not found']);
    //     return view('admin.orders.order_details', compact('order'));
    // }
    
    public function update_orderstatus($id,Request $request)
    {
        $order = Order::find($id);
        if(!$order)
        return redirect()->route('admin.orders')->with(['error' => 'This order not found']);
        $order::where('id',$id)->update([
            'status'=>$request->order_status
        ]);
        return redirect()->route('admin.orders.orderdetails',$id)->with(['success' => 'Status updated successfully']);
    }

    public function viewinvoice($id)
    {
        $order = Order::findOrFail($id);

        if(!$order)
        return redirect()->route('admin.orders')->with(['error' => 'This order not found']);
        return view('admin.invoice.generate-invoice', compact('order'));
    }

    public function generateinvoice($id)
    {
        $order = Order::findOrFail($id);
        $order_details=Orderitem::where('order_id',$id)->get();
        $data=['order' => $order];
        $pdf = Pdf::loadView('admin.invoice.generate-invoice', $data);
    return $pdf->download('invoice'.$order->id.'.pdf');
        if(!$order)
        return redirect()->route('admin.orders')->with(['error' => 'This order not found']);
        return view('admin.invoice.generate-invoice', compact('order','order_details'));
    }
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    // All Orders
    public function all_orders(Request $request)
    {
        

        $date = $request->date;
        $sort_search = null;
        $delivery_status = null;
        $payment_status = '';

        $orders = Order::orderBy('id', 'desc');
        $admin_user_id = User::where('user_type', 'admin')->first()->id;

        // && Auth::user()->can('view_seller_orders')
        if (Route::currentRouteName() == 'inhouse_orders.index' && Auth::user()->can('view_inhouse_orders')) {
            $orders = $orders->where('orders.seller_id', '=', $admin_user_id);
        } else if (Route::currentRouteName() == 'seller_orders.index' ) {
            $orders = $orders->where('orders.seller_id', '!=', $admin_user_id);
        } else if (Route::currentRouteName() == 'pick_up_point.index' && Auth::user()->can('view_pickup_point_orders')) {
            $orders->where('shipping_type', 'pickup_point')->orderBy('code', 'desc');
            if (Auth::user()->user_type == 'staff' && Auth::user()->staff->pick_up_point != null) {
                $orders->where('shipping_type', 'pickup_point')
                    ->where('pickup_point_id', Auth::user()->staff->pick_up_point->id);
            }
        } else if (Route::currentRouteName() == 'all_orders.index' && Auth::user()->can('view_all_orders') ) {
        } else {
            abort(403);
        }

        if ($request->search) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }
        if ($request->payment_status != null) {
            $orders = $orders->where('payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($date != null) {
            $orders = $orders->where('created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])) . '  00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])) . '  23:59:59');
        }
        $orders = $orders->paginate(15);
       // return Route::currentRouteName();
       return view('admin.orders.index', compact('orders', 'sort_search', 'payment_status', 'delivery_status', 'date'));
    }

    public function show($id)
    {

         $order = Order::findOrFail(decrypt($id));
        //  return $order;
        $order_shipping_address = json_decode($order->shipping_address);
        $delivery_boys = User::where('city', $order_shipping_address->city)
            ->where('user_type', 'delivery_boy')
            ->get();

        $order->viewed = 1;
        $order->save();

        $shop = Shop::where('user_id',$order->seller_id)->first();
         return view('admin.orders.order_details', compact('order', 'delivery_boys','shop'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $carts = Cart::where('user_id', Auth::user()->id)
            ->get();

        if ($carts->isEmpty()) {
            return redirect()->route('home')->with(['error'=>tran('Your cart is empty')]);
        }

        $address = Address::where('id', $carts[0]['address_id'])->first();

        $shippingAddress = [];
        if ($address != null) {
            $shippingAddress['name']        = Auth::user()->name;
            $shippingAddress['email']       = Auth::user()->email;
            $shippingAddress['address']     = $address->address;
            $shippingAddress['country']     = $address->country->name;
            $shippingAddress['state']       = $address->state->name;
            $shippingAddress['city']        = $address->city->name;
            $shippingAddress['postal_code'] = $address->postal_code;
            $shippingAddress['phone']       = $address->phone;
            if ($address->latitude || $address->longitude) {
                $shippingAddress['lat_lang'] = $address->latitude . ',' . $address->longitude;
            }
        }

        $combined_order = new CombinedOrder;
        $combined_order->user_id = Auth::user()->id;
        $combined_order->shipping_address = json_encode($shippingAddress);
        $combined_order->save();

        $seller_products = array();
        foreach ($carts as $cartItem) {
            $product_ids = array();
            $product = Product::find($cartItem['product_id']);
            if (isset($seller_products[$product->user_id])) {
                $product_ids = $seller_products[$product->user_id];
            }
            array_push($product_ids, $cartItem);
            $seller_products[$product->user_id] = $product_ids;
        }

        foreach ($seller_products as $seller_product) {
            $order = new Order;
            $order->combined_order_id = $combined_order->id;
            $order->user_id = Auth::user()->id;
            $order->shipping_address = $combined_order->shipping_address;

            $order->additional_info = $request->additional_info;

            // $order->shipping_type = $carts[0]['shipping_type'];
            // if ($carts[0]['shipping_type'] == 'pickup_point') {
            //     $order->pickup_point_id = $cartItem['pickup_point'];
            // }
            // if ($carts[0]['shipping_type'] == 'carrier') {
            //     $order->carrier_id = $cartItem['carrier_id'];
            // }

            $order->payment_type = $request->payment_option;
            $order->delivery_viewed = '0';
            $order->payment_status_viewed = '0';
            $order->code = date('Ymd-His') . rand(10, 99);
            $order->date = strtotime('now');
            $order->save();

            $subtotal = 0;
            $tax = 0;
            $shipping = 0;
            $coupon_discount = 0;

            //Order Details Storing
            foreach ($seller_product as $cartItem) {
                $product = Product::find($cartItem['product_id']);

                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                $tax +=  cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                $coupon_discount += $cartItem['discount'];

                $product_variation = $cartItem['variation'];
                $product_selectimg=$cartItem['selectedimg'];
                $product_stock = $product->stocks->where('variant', $product_variation)->first();
                if ($product->digital != 1 && $cartItem['quantity'] > $product_stock->qty) {
                  
                    $order->delete();
                    return redirect()->route('cart')->send()->with(['warning'=>tran('The requested quantity is not available for    ') . $product->name]);
                } elseif ($product->digital != 1) {
                    $product_stock->qty -= $cartItem['quantity'];
                    $product_stock->save();
                }

                $order_detail = new OrderDetail;
                $order_detail->order_id = $order->id;
                $order_detail->seller_id = $product->user_id;
                $order_detail->product_id = $product->id;
                $order_detail->variation = $product_variation;
                $order_detail->selectedimg = $product_selectimg;
                $order_detail->price = cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                $order_detail->tax = cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                $order_detail->shipping_type = $cartItem['shipping_type'];
                $order_detail->product_referral_code = $cartItem['product_referral_code'];
                $order_detail->shipping_cost = $cartItem['shipping_cost'];

                $shipping += $order_detail->shipping_cost;
                //End of storing shipping cost

                $order_detail->quantity = $cartItem['quantity'];

              
                
                $order_detail->save();

                $product->num_of_sale += $cartItem['quantity'];
                $product->save();

                $order->seller_id = $product->user_id;
                $order->shipping_type = $cartItem['shipping_type'];
                
                if ($cartItem['shipping_type'] == 'pickup_point') {
                    $order->pickup_point_id = $cartItem['pickup_point'];
                }
                if ($cartItem['shipping_type'] == 'carrier') {
                    $order->carrier_id = $cartItem['carrier_id'];
                }

                if ($product->added_by == 'seller' && $product->user->seller != null) {
                    $seller = $product->user->seller;
                    $seller->num_of_sale += $cartItem['quantity'];
                    $seller->save();
                }

               
            }

            $order->grand_total = $subtotal + $tax + $shipping;

            if ($seller_product[0]->coupon_code != null) {
                $order->coupon_discount = $coupon_discount;
                $order->grand_total -= $coupon_discount;

                $coupon_usage = new CouponUsage;
                $coupon_usage->user_id = Auth::user()->id;
                $coupon_usage->coupon_id = Coupon::where('code', $seller_product[0]->coupon_code)->first()->id;
                $coupon_usage->save();
            }

            $combined_order->grand_total += $order->grand_total;

            $order->save();
        }

        $combined_order->save();

        foreach($combined_order->orders as $order){
            NotificationUtility::sendOrderPlacedNotification($order);
        }

        $request->session()->put('combined_order_id', $combined_order->id);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        if ($order != null) {
            foreach ($order->orderDetails as $key => $orderDetail) {
                try {
                    $product_stock = ProductStock::where('product_id', $orderDetail->product_id)->where('variant', $orderDetail->variation)->first();
                    if ($product_stock != null) {
                        $product_stock->qty += $orderDetail->quantity;
                        $product_stock->save();
                    }
                } catch (\Exception $e) {
                }
                $orderDetail->delete();
            }
            $order->delete();
            return back()->with(['success'=>tran('Order has been deleted successfully')]);
        } else {
           return back()->with(['error'=>tran('An error occured , please try again later')]);
        } 
    }

    public function bulk_order_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $order_id) {
                $this->destroy($order_id);
            }
        }
        return 1;
    }

    public function order_details(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->save();
        return view('seller.order_details_seller', compact('order'));
    }

    public function update_delivery_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->delivery_viewed = '0';
        $order->delivery_status = $request->status;
        $order->save();

        if ($request->status == 'cancelled' && $order->payment_type == 'wallet') {
            $user = User::where('id', $order->user_id)->first();
            $user->balance += $order->grand_total;
            $user->save();
        }

        if (Auth::user()->user_type == 'seller') {
            foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();

                if ($request->status == 'cancelled') {
                    $variant = $orderDetail->variation;
                    if ($orderDetail->variation == null) {
                        $variant = '';
                    }

                    $product_stock = ProductStock::where('product_id', $orderDetail->product_id)
                        ->where('variant', $variant)
                        ->first();

                    if ($product_stock != null) {
                        $product_stock->qty += $orderDetail->quantity;
                        $product_stock->save();
                    }
                }
            }
        } 
        else {
            foreach ($order->orderDetails as $key => $orderDetail) {

                $orderDetail->delivery_status = $request->status;
                $orderDetail->save();

                if ($request->status == 'cancelled') {
                    $variant = $orderDetail->variation;
                    if ($orderDetail->variation == null) {
                        $variant = '';
                    }

                    $product_stock = ProductStock::where('product_id', $orderDetail->product_id)
                        ->where('variant', $variant)
                        ->first();

                    if ($product_stock != null) {
                        $product_stock->qty += $orderDetail->quantity;
                        $product_stock->save();
                    }
                }

                
            }
        }
       

        //sends Notifications to user
        NotificationUtility::sendNotification($order, $request->status);
        if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
            $request->device_token = $order->user->device_token;
            $request->title = "Order updated !";
            $status = str_replace("_", "", $order->delivery_status);
            $request->text = " Your order {$order->code} has been {$status}";

            $request->type = "order";
            $request->id = $order->id;
            $request->user_id = $order->user->id;

            NotificationUtility::sendFirebaseNotification($request);
        }


        
        $deliv_status="";  
        if ($order->delivery_status == 'confirmed'){
            $deliv_status=tran('Confirmed');
        }else if ($order->delivery_status == 'picked_up'){
            $deliv_status=tran('Picked Up');
        }else if ($order->delivery_status == 'on_the_way'){
            $deliv_status=tran('On The Way');
        }else if ($order->delivery_status == 'delivered'){ 
            $deliv_status=tran('Delivered');
        }else if ($order->delivery_status == 'cancelled'){
            $deliv_status=tran('Cancel');
        }
         return $deliv_status;
    }

    public function update_tracking_code(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->tracking_code = $request->tracking_code;
        $order->save();

        return 1;
    }

    public function update_payment_status(Request $request)
    {
        $order = Order::findOrFail($request->order_id);
        $order->payment_status_viewed = '0';
        $order->save();

        if (Auth::user()->user_type == 'seller') {
            foreach ($order->orderDetails->where('seller_id', Auth::user()->id) as $key => $orderDetail) {
                $orderDetail->payment_status = $request->status;
                $orderDetail->save();
            }
        } else {
            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->payment_status = $request->status;
                $orderDetail->save();
            }
        }

        $status = 'paid';
        foreach ($order->orderDetails as $key => $orderDetail) {
            if ($orderDetail->payment_status != 'paid') {
                $status = 'unpaid';
            }
        }
        $order->payment_status = $status;
        $order->save();

        
        if (
            $order->payment_status == 'paid' &&
            $order->commission_calculated == 0
        ) {
            calculateCommissionAffilationClubPoint($order);
        }

        //sends Notifications to user
        NotificationUtility::sendNotification($order, $request->status);
        if (get_setting('google_firebase') == 1 && $order->user->device_token != null) {
            $request->device_token = $order->user->device_token;
            $request->title = "Order updated !";
            $status = str_replace("_", "", $order->payment_status);
            $request->text = " Your order {$order->code} has been {$status}";

            $request->type = "order";
            $request->id = $order->id;
            $request->user_id = $order->user->id;

            NotificationUtility::sendFirebaseNotification($request);
        }


        
        return $order->payment_status;
    }

  
}
