<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PickupPoint;
use App\Models\PickupPointTranslation;
class PickupPointsController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:pickup_point_setup'])->only('index','create','edit','destroy');
    }
    public function index(Request $request)
    {
        $sort_search =null;
        $pickup_points = PickupPoint::orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $pickup_points = $pickup_points->where('name', 'like', '%'.$sort_search.'%');
        }
        $pickup_points = $pickup_points->get();
        return view('admin.settings.pickup_points.index', compact('pickup_points','sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.settings.pickup_points.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pickup_point = new PickupPoint;
        $pickup_point->name = $request->name;
        $pickup_point->address = $request->address;
        $pickup_point->phone = $request->phone;
        $pickup_point->pick_up_status = $request->pick_up_status;
        $pickup_point->staff_id = $request->staff_id;
        if ($pickup_point->save()) {
            // $pickup_point_translation = PickupPointTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'pickup_point_id' => $pickup_point->id]);
            // $pickup_point_translation->name = $request->name;
            // $pickup_point_translation->address = $request->address;
            // $pickup_point_translation->save();
            return redirect()->route('pick_up_points.index')->with(['success' => 'PicupPoint has been inserted successfully']);
        }
        else{
            return back()->with(['error' => 'Something went wrong']);
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
    public function edit(Request $request, $id)
    {
        $lang           = $request->lang;
        $pickup_point   = PickupPoint::findOrFail($id);
        return view('admin.settings.pickup_points.edit', compact('pickup_point','lang'));
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
        $pickup_point = PickupPoint::findOrFail($id);
        $pickup_point->name = $request->name;
        $pickup_point->address = $request->address;
        $pickup_point->phone = $request->phone;
        $pickup_point->pick_up_status = $request->pick_up_status;
        $pickup_point->staff_id = $request->staff_id;
        if ($pickup_point->save()) {
            return redirect()->route('pick_up_points.index')->with(['success' => 'PicupPoint has been updated successfully']);
        }
        else{
            return back()->with(['error' => 'Something went wrong']);
           
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pickup_point = PickupPoint::findOrFail($id);
        $pickup_point->pickup_point_translations()->delete();

        if(PickupPoint::destroy($id)){
            return redirect()->route('pick_up_points.index')->with(['success' => 'PicupPoint has been deleted successfully']);
        }
        else{
            return back()->with(['error' => 'Something went wrong']);
        }
    }
}
