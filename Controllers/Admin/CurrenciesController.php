<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Currency;

class CurrenciesController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:currency_setup'])->only('currency','create','edit');
    } 
    public function changeCurrency(Request $request)
    {
        $currency = Currency::where('code', $request->currency_code)->first();
        $request->session()->put('currency_code', $request->currency_code);
        $request->session()->put('currency_symbol', $currency->symbol);
        $request->session()->put('currency_exchange_rate', $currency->exchange_rate);
    }

    public function currency(Request $request)
    {
        $sort_search =null;
        $currencies = Currency::orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $currencies = $currencies->where('name', 'like', '%'.$sort_search.'%');
        }
        $currencies = $currencies->get();

        $active_currencies = Currency::where('status', 1)->get();
        return view('admin.settings.currencies.index', compact('currencies', 'active_currencies','sort_search'));
    }

    public function updateYourCurrency(Request $request)
    {
        $currency = Currency::findOrFail($request->id);
        $currency->name = $request->name;
        $currency->symbol = $request->symbol;
        $currency->code = $request->code;
        $currency->exchange_rate = $request->exchange_rate;
        $currency->status = $currency->status;
        if($currency->save()){
           
            return redirect()->route('currency.index')->with(['success'=>'Currency updated successfully']);
        }
        else {
            return redirect()->route('currency.index')->with(['error'=>'Something went wrong']);
        }
    }

    public function create()
    {
        return view('admin.settings.currencies.create');
    }

    public function edit(Request $request)
    {
        $currency = Currency::findOrFail($request->id);
        return view('admin.settings.currencies.edit', compact('currency'));
    }

    public function store(Request $request)
    {
        $currency = new Currency;
        $currency->name = $request->name;
        $currency->symbol = $request->symbol;
        $currency->code = $request->code;
        $currency->exchange_rate = $request->exchange_rate;
        $currency->status = '0';
        if($currency->save()){
            return redirect()->route('currency.index')->with(['success'=>'Currency added successfully']);
        }
        else {
            return redirect()->route('currency.index')->with(['error'=>'Something went wrong']);
        }
    }

    public function update_status(Request $request)
    {
        $currency = Currency::findOrFail($request->id);
        if($request->status == 0){
            if (get_setting('system_default_currency') == $currency->id) {
                return 0;
            }
        }
        $currency->status = $request->status;
        $currency->save();
        return 1;
    }
}
