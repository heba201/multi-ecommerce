<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\Country;
use App\Models\Address;
use App\Models\User;
class StateController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:manage_shipping_states'])->only('index','edit');
    }
    public function index(Request $request)
    {
        $sort_country = $request->sort_country;
        $sort_state = $request->sort_state;

        $state_queries = State::query();
        if ($request->sort_state) {
            $state_queries->where('name', 'like', "%$sort_state%");
        }
        if ($request->sort_country) {
            $state_queries->where('country_id', $request->sort_country);
        }

        $states = $state_queries->get();
        return view('admin.settings.shippings.shipping_configuration.states.index', compact('states', 'sort_country', 'sort_state'));
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
        $state = new State;

        $state->name        = $request->name;
        $state->country_id  = $request->country_id;

        $state->save();
        return back()->with(['success'=>'State has been inserted successfully']);
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
        $state  = State::findOrFail($id);
        $countries = Country::where('status', 1)->get();

        return view('admin.settings.shippings.shipping_configuration.states.edit', compact('countries', 'state'));
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
        $state = State::findOrFail($id);

        $state->name        = $request->name;
        $state->country_id  = $request->country_id;

        $state->save();
        return back()->with(['success'=>'State has been updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $state=  State::find($id);
      
       if($state){
        $cities=$state->cities;
        $users=User::where('state_id',$id)->get();
        $addresses=Address::where('state_id',$id)->get();
        if($cities->count() == 0 && $users->count() == 0 && $addresses->count() == 0){
            State::destroy($id);
            return redirect()->route('states.index')->with(['success'=>tran('State has been deleted successfully')]);
        }else return redirect()->route('states.index')->with(['error'=>tran('State can not be deleted')]);
        
       }else return redirect()->route('states.index')->with(['error'=>tran('State not found')]);
    }

    public function updateStatus(Request $request)
    {
        $state = State::findOrFail($request->id);
        $state->status = $request->status;
        $state->save();

        if ($state->status) {
            foreach ($state->cities as $city) {
                $city->status = 1;
                $city->save();
            }
        }
        return 1;
    }
}
