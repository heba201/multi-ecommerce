<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tax;
class TaxesController extends Controller
{
    
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:vat_&_tax_setup'])->only('index','create','edit','destroy');
    }
    public function index()
    {
        $all_taxes = Tax::orderBy('created_at', 'desc')->get();
        return view('admin.settings.taxes.index', compact('all_taxes'));
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
    public function store(Request $request)
    {
        $tax = new Tax;
        $tax->name = $request->name;
//        $pickup_point->address = $request->address;
        
        if ($tax->save()) {
            return redirect()->route('tax.index')->with(['success' => 'Tax has been inserted successfully']);

        }
        else{
    
            return redirect()->route('tax.index')->with(['error' => 'Something went wrong']);
        }
    }

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
        $tax = Tax::findOrFail($id);
        return view('admin.settings.taxes.edit', compact('tax'));
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
        $tax = Tax::findOrFail($id);
        $tax->name = $request->name;
//        $language->code = $request->code;
        if($tax->save()){
          
            return redirect()->route('tax.index')->with(['success' => 'Tax has been updated successfully']);
        }
        else{
            return redirect()->route('tax.index')->with(['error' => 'Something went wrong']);
        }
    }
    
    public function change_tax_status(Request $request) {
        $tax = Tax::findOrFail($request->id);
        if($tax->tax_status == 1) {
            $tax->tax_status = 0;
        } else {
            $tax->tax_status = 1;
        }
        
        if($tax->save()) {
            return 1;
        } 
        return 0;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        if(Tax::destroy($id)){
            
            return redirect()->route('tax.index')->with(['success' => 'Tax has been deleted successfully']);
            
        }
        else{
            return redirect()->route('tax.index')->with(['error' => 'Something went wrong']);
        }
    }
}
