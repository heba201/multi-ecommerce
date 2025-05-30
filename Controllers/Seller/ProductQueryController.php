<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\ProductQuery;
use App\Utility\NotificationUtility;
use Auth;
use Illuminate\Http\Request;

class ProductQueryController extends Controller
{
    /**
     * Retrieve queries that belongs to current seller
     */
    public function index()
    {
        $queries = ProductQuery::where('seller_id', Auth::id())->latest()->get();
        return view('seller.product_query.index', compact('queries'));
    }
    /**
     * Retrieve specific query using query id.
     */
    public function show($id)
    {
        $query = ProductQuery::find(decrypt($id));
        return view('seller.product_query.show', compact('query'));
    }
    /**
     * Store reply against the question from seller panel
     */

    public function reply(Request $request, $id)
    {
        $this->validate($request, [
            'reply' => 'required',
        ]);
        $query = ProductQuery::find($id);
        $query->reply = $request->reply;
        $query->save();
         NotificationUtility::sendNotification_productinquery($query);
        return redirect()->route('seller.product_query.index')->with(['success'=>tran('Replied successfully!')]);
    }
}
