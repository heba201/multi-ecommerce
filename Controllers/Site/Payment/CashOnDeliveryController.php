<?php

namespace App\Http\Controllers\Site\Payment;

use App\Http\Controllers\Controller;

class CashOnDeliveryController extends Controller
{
    public function pay(){
       return redirect()->route('payment.order_confirmed')->with(["success"=>tran('Your order has been placed successfully')]);
    }
}
