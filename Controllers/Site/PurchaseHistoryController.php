<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Models\Order;
use App\Models\Upload;
use App\Models\Product;
class PurchaseHistoryController extends Controller
{
    public function index()
    {
        $orders = Order::with('orderDetails')->where('user_id', Auth::user()->id)->orderBy('code', 'desc')->paginate(10);
        return view('front.user.purchase_history', compact('orders'));
    }

    public function digital_index()
    {
        $orders = DB::table('orders')
                        ->orderBy('code', 'desc')
                        ->join('order_details', 'orders.id', '=', 'order_details.order_id')
                        ->join('products', 'order_details.product_id', '=', 'products.id')
                        ->where('orders.user_id', Auth::user()->id)
                        ->where('products.digital', '1')
                        ->where('order_details.payment_status', 'paid')
                        ->select('order_details.id')
                        ->paginate(15);
        return view('frontend.user.digital_purchase_history', compact('orders'));
    }

    public function purchase_history_details($id)
    {
        $order = Order::findOrFail(decrypt($id));
        $order->delivery_viewed = 1;
        $order->payment_status_viewed = 1;
        $order->save();
        return view('front.user.order_details_customer', compact('order'));
    }

    public function download(Request $request)
    {
        $product = Product::findOrFail(decrypt($request->id));
        $downloadable = false;
        foreach (Auth::user()->orders as $key => $order) {
            foreach ($order->orderDetails as $key => $orderDetail) {
                if ($orderDetail->product_id == $product->id && $orderDetail->payment_status == 'paid') {
                    $downloadable = true;
                    break;
                }
            }
        }
        if ($downloadable) {
            $upload = Upload::findOrFail($product->file_name);
            if (env('FILESYSTEM_DRIVER') == "s3") {
                return \Storage::disk('s3')->download($upload->file_name, $upload->file_original_name . "." . $upload->extension);
            } else {
                if (file_exists(base_path('public/' . $upload->file_name))) {
                    return response()->download(base_path('public/' . $upload->file_name));
                }
            }
        } else {
           
            return back()->with(["success"=>tran('You cannot download this product at this product.')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function order_cancel($id)
    {
        $order = Order::where('id', $id)->where('user_id', auth()->user()->id)->first();
        if($order && ($order->delivery_status == 'pending' && $order->payment_status == 'unpaid')) {
            $order->delivery_status = 'cancelled';
            $order->save();

            foreach ($order->orderDetails as $key => $orderDetail) {
                $orderDetail->delivery_status = 'cancelled';
                $orderDetail->save();
                product_restock($orderDetail);
            }

          
            $msg=tran('Order has been canceled successfully');
        } else {
     
            $msg=tran('Something went wrong');
        }

        return back()->with(["success"=> $msg]);
    }


    public function purchase_history_details_edit($id)
    {
        $order = Order::findOrFail(decrypt($id));
         if($order->delivery_status == 'pending' && $order->payment_status == 'unpaid'){
            return view('frontend.user.order_details_edit_customer', compact('order'));
         }else{
            return back();
         }
    }


    public function trackOrder(Request $request)
    {
        if ($request->has('order_code')) {
            $order = Order::where('code', $request->order_code)->first();
            //return $order;
            if ($order != null) {
                return view('front.user.track_order', compact('order'));
            }
        }
        return view('front.user.track_order');
    }

}
