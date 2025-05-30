<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductQuery;
use App\Models\User;
use Auth;
class ProductQueryController extends Controller
{
     /**
     * Retrieve queries that belongs to current seller
     */
    public function index()
    {
        $admin_id = User::where('user_type','admin')->first()->id;
        $queries = ProductQuery::where('seller_id', $admin_id)->latest()->get();
        //return $queries ;
        return view('admin.support.product_query.index', compact('queries'));
    }

    /**
     * Retrieve specific query using query id.
     */
    public function show($id)
    {
        $query = ProductQuery::find(decrypt($id));
        return view('admin.support.product_query.show', compact('query'));
    }

    /**
     * store products queries through the ProductQuery model
     * data comes from product details page
     * authenticated user can leave queries about the product
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'question' => 'required|string',
        ]);
        $product = Product::find($request->product);

        $query = new ProductQuery();
        $query->customer_id = Auth::id();
        $query->seller_id = $product->user_id;
        $query->product_id = $product->id;
        $query->question = $request->question;
        $query->save();
        return redirect()->back()->with(["success"=>tran('Your query has been submitted successfully')]);
    }

    /**
     * Store reply against the question from Admin panel
     */

    public function reply(Request $request, $id)
    {
        $this->validate($request, [
            'reply' => 'required',
        ]);
        $query = ProductQuery::find($id);
        $query->reply = $request->reply;
        $query->save();       
       return redirect()->route('product_query.index')->with(["success"=>tran('Replied successfully!')]);
    }
}
