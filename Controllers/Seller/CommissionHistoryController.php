<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Models\CommissionHistory;
use Auth;

class CommissionHistoryController extends Controller
{
    public function index(Request $request) {
        $seller_id = null;
        $date_range = null;
        
        $commission_history = CommissionHistory::where('seller_id', Auth::user()->id)->orderBy('created_at', 'desc');
        
        if ($request->date_range) {
            $date_range = $request->date_range;
            $date_range1 = explode(" - ", $request->date_range,3);
            $commission_history = $commission_history->whereDATE('created_at', '>=', date('Y-m-d',strtotime($date_range1[0])));
            $commission_history = $commission_history->whereDATE('created_at', '<=', date('Y-m-d',strtotime($date_range1[1])));
        }
       // return date('Y-m-d',strtotime($date_range1[0]));
        $commission_history = $commission_history->get();
        return view('seller.commission_history.index', compact('commission_history', 'seller_id', 'date_range'));
    }
}
