<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\CityTranslation;
use App\Models\State;
use App\Models\Address;
use App\Models\User;
class CityController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:manage_shipping_cities'])->only('index','create','destroy');
    }
    public function index(Request $request)
    {
        $sort_city = $request->sort_city;
        $sort_state = $request->sort_state;
        $cities_queries = City::query();
        if($request->sort_city) {
            $cities_queries->where('name', 'like', "%$sort_city%");
        }
        if($request->sort_state) {
            $cities_queries->where('state_id', $request->sort_state);
        }
        $cities = $cities_queries->orderBy('status', 'desc')->paginate(70);
        $states = State::where('status', 1)->get();
        return view('admin.settings.shippings.shipping_configuration.cities.index', compact('cities', 'states', 'sort_city', 'sort_state'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $city = new City;

        $city->name = $request->name;
        $city->cost = $request->cost;
        $city->state_id = $request->state_id;

        $city->save();

        return back()->with(['success'=>tran('City has been inserted successfully')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function edit(Request $request, $id)
     {
         $lang  = $request->lang;
         $city  = City::findOrFail($id);
         $states = State::where('status', 1)->get();
         return view('admin.settings.shippings.shipping_configuration.cities.edit', compact('city', 'lang', 'states'));
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
        $city = City::findOrFail($id);
        $city->name = $request->name;
        $city->state_id = $request->state_id;
        $city->cost = $request->cost;
        $city->save();
        return back()->with(['success'=>tran('City has been updated successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $city = City::findOrFail($id);
        if($city){
        $users=User::where('city_id',$id)->get();
        $addresses=Address::where('city_id',$id)->get();
        if($users->count() == 0 && $addresses->count() == 0){
        $city->city_translations()->delete();
        City::destroy($id);
        return redirect()->route('cities.index')->with(['success'=>tran('City has been deleted successfully')]);
        }else   return redirect()->route('cities.index')->with(['error'=>tran('City can not be deleted')]);
    }else return redirect()->route('cities.index')->with(['error'=>tran('City not found')]);     
    }

    public function updateStatus(Request $request){
        $city = City::findOrFail($request->id);
        $city->status = $request->status;
        $city->save();

        return 1;
    }
}
