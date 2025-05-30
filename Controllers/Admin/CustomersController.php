<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;
use App\Models\Message; 
use App\Models\Conversation;
class CustomersController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:view_all_customers'])->only('index');
        $this->middleware(['permission:login_as_customer'])->only('login');
        $this->middleware(['permission:ban_customer'])->only('ban');
        $this->middleware(['permission:delete_customer'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $users = User::where('user_type', 'customer')->where('email_verified_at', '!=', null)->orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $users->where(function ($q) use ($sort_search){
                $q->where('name', 'like', '%'.$sort_search.'%')->orWhere('email', 'like', '%'.$sort_search.'%');
            });
        }
        $users = $users->paginate(15);
        return view('admin.customers.index', compact('users', 'sort_search'));
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = User::findOrFail($id);
        $customer_id=$customer->id;
        if(!$customer)
          return redirect()->route('customers.index')->with(['error'=>tran('This Customer may have been deleted or may not exist')]);
       if($customer->user_type=="customer"){
         $conversations=Conversation::where(function ($query) use($customer_id) {
            $query->where('sender_id', '=', $customer_id)
                  ->orWhere('receiver_id', '=', $customer_id);
        })->get();
        $messages=Message::where('user_id', $customer->id)->get();
        if($customer->orders()->count() == 0 && $conversations ->count() == 0 && $messages->count() == 0 && $customer->product_queries() == 0){
            $customer->carts()->delete(); 
            User::destroy($id);
            return redirect()->route('customers.index')->with(['success'=>tran('Customer has been deleted successfully')]);
        }else  return redirect()->route('customers.index')->with(['error'=>tran('Customer cannot be deleted')]);
        
       }else  return redirect()->route('customers.index')->with(['error'=>tran('Customer cannot be deleted')]);
    }
    
    public function bulk_customer_delete(Request $request) {
        if($request->id) {
            foreach ($request->id as $customer_id) {
                $customer = User::findOrFail($customer_id);
                $customer->customer_products()->delete(); 
                $this->destroy($customer_id);
            }
        }
        
        return 1;
    }

    public function login($id)
    {
        $user = User::findOrFail(decrypt($id));

        auth()->login($user, true);

        return redirect()->route('home');
    }

}
