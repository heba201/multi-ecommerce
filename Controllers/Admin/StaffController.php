<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Role;
use App\Models\User;
use Hash;
class StaffController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:view_all_staffs'])->only('index');
        $this->middleware(['permission:add_staff'])->only('create');
        $this->middleware(['permission:edit_staff'])->only('edit');
        $this->middleware(['permission:delete_staff'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $staffs = Staff::paginate(10);
        return view('admin.staff.staffs.index', compact('staffs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('id','!=',1)->orderBy('id', 'desc')->get();
        return view('admin.staff.staffs.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(User::where('email', $request->email)->first() == null){
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->mobile;
            $user->user_type = "staff";
            $user->password = Hash::make($request->password);
            if($user->save()){
                $staff = new Staff;
                $staff->user_id = $user->id;
                $staff->role_id = $request->role_id;
                $user->assignRole(Role::findOrFail($request->role_id)->name);
                if($staff->save()){
                    
                    return redirect()->route('staffs.index')->with(['success'=>'Staff has been inserted successfully']);
                }
            }
        }
        return back()->with(['error'=>'Email already used']);
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
        $staff = Staff::findOrFail(decrypt($id));
        $roles = $roles = Role::where('id','!=',1)->orderBy('id', 'desc')->get();
        return view('admin.staff.staffs.edit', compact('staff', 'roles'));
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
        $staff = Staff::findOrFail($id);
        $user = $staff->user;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->mobile;
        if(strlen($request->password) > 0){
            $user->password = Hash::make($request->password);
        }
        if($user->save()){
            $staff->role_id = $request->role_id;
            if($staff->save()){
                $user->syncRoles(Role::findOrFail($request->role_id)->name);
                return redirect()->route('staffs.index')->with(['success'=>'Staff has been updated  successfully']);
            }
        }
        return back()->with(['error'=>'Something went wrong']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy(Staff::findOrFail($id)->user->id);
        if(Staff::destroy($id)){
            return redirect()->route('staffs.index')->with(['success'=>'Staff has been deleted  successfully']);
        }
        return back()->with(['error'=>'Something went wrong']);
    }
}
