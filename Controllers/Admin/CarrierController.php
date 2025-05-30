<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CarrierRequest;
use App\Models\Carrier;
use App\Models\CarrierRange;
use App\Models\CarrierRangePrice;
use App\Models\Zone;
class CarrierController extends Controller
{
    
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:manage_carriers'])->only('index','create','edit','destroy');
    }
    public function index()
    {
        $carriers = Carrier::get();
        return view('admin.settings.shippings.shipping_configuration.carriers.index', compact('carriers'));
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $zones = Zone::get();
        return view('admin.settings.shippings.shipping_configuration.carriers.create',compact('zones'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CarrierRequest $request)
    {
        $carrier                = new Carrier;
        $logo= "";
        $carrier->name          = $request->carrier_name;
        $carrier->transit_time  = $request->transit_time;
        

        if ($request->has('logo') && $request->logo !="") {
            $logo = uploadImage('carriers', $request->logo);
        } 
        $carrier->logo = $logo;
        $free_shipping          = isset($request->shipping_type) ? 1 : 0;
        $carrier->free_shipping = $free_shipping;
        $carrier->save();

        // if not free shipping, then add the carrier ranges and prices
        if($free_shipping == 0){
            for($i=0; $i < count($request->delimiter1); $i++){

                // Add Carrier ranges
                $carrier_range                  = new CarrierRange;
                $carrier_range->carrier_id      = $carrier->id;
                $carrier_range->billing_type    = $request->billing_type;
                $carrier_range->delimiter1      = $request->delimiter1[$i];
                $carrier_range->delimiter2      = $request->delimiter2[$i];
                $carrier_range->save();

                // Add carrier range prices
                foreach($request->zones as $zone){
                    $carrier_range_price =  new CarrierRangePrice;
                    $carrier_range_price->carrier_id = $carrier->id;
                    $carrier_range_price->carrier_range_id = $carrier_range->id;
                    $carrier_range_price->zone_id = $zone;
                    $carrier_range_price->price = $request->carrier_price[$zone][$i];
                    $carrier_range_price->save();
                }
            }
        }
        
        return 1;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $carrier = Carrier::findOrFail($id);
        $zones = Zone::get();
        return view('admin.settings.shippings.shipping_configuration.carriers.edit',compact('zones','carrier'));
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CarrierRequest $request, $id)
    {
        $carrier                = Carrier::findOrfail($id);
        $carrier->name          = $request->carrier_name;
        $carrier->transit_time  = $request->transit_time;
        $logo = $carrier->logo  ;
        $free_shipping          = isset($request->shipping_type) ? 1 : 0;
        $carrier->free_shipping = $free_shipping;
        if ($request->has('logo') && $request->logo !="") {
            $logo = uploadImage('carriers', $request->logo);
        } 
        $carrier->logo = $logo;
        $carrier->save();

        $carrier->carrier_ranges()->delete();
        $carrier->carrier_range_prices()->delete();

        // if not free shipping, then add the carrier ranges and prices
        if($free_shipping == 0){
            for($i=0; $i < count($request->delimiter1); $i++){

                // Add Carrier ranges
                $carrier_range                  = new CarrierRange;
                $carrier_range->carrier_id      = $carrier->id;
                $carrier_range->billing_type    = $request->billing_type;
                $carrier_range->delimiter1      = $request->delimiter1[$i];
                $carrier_range->delimiter2      = $request->delimiter2[$i];
                $carrier_range->save();

                // Add carrier range prices
                foreach($request->zones as $zone){
                    $carrier_range_price =  new CarrierRangePrice;
                    $carrier_range_price->carrier_id = $carrier->id;
                    $carrier_range_price->carrier_range_id = $carrier_range->id;
                    $carrier_range_price->zone_id = $zone;
                    $carrier_range_price->price = $request->carrier_price[$zone][$i];
                    $carrier_range_price->save();
                }
            }
        }
        return back()->with(['success'=>tran('New carrier has been added successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $carrier = Carrier::findOrFail($id);
        $carrier->carrier_ranges()->delete();
        $carrier->carrier_range_prices()->delete();
        Carrier::destroy($id);
        return redirect()->back()->with(['success'=>tran('Carrier has been deleted successfully')]);
    }

    // Carrier status Update
    public function updateStatus(Request $request)
    {
        $carrier = Carrier::findOrFail($request->id);
        $carrier->status = $request->status;
        if($carrier->save()){
            return 1;
        }
        return 0;
    }
}
