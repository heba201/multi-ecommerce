<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role; 
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;
use App\Http\Requests\RolesRequest;
class RolesController extends Controller
{
     
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:view_staff_roles'])->only('index');
        $this->middleware(['permission:add_staff_role'])->only('create');
        $this->middleware(['permission:edit_staff_role'])->only('edit');
        $this->middleware(['permission:delete_staff_role'])->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::where('id','!=',1)->paginate(10);
        return view('admin.staff.staff_roles.index', compact('roles'));

        // $roles = Role::paginate(10);
        // return view('backend.staff.staff_roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.staff.staff_roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->permissions);
        $role = Role::create(['name' => $request->name]);
        $role->name = $request->name;
        $role->givePermissionTo($request->permissions);
        return redirect()->route('roles.index')->with(['success'=>'New Role has been added successfully']);
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
        $lang = $request->lang;
        $role = Role::findOrFail($id);
        return view('admin.staff.staff_roles.edit', compact('role','lang'));
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
        $role = Role::findOrFail($id);

            $role->name = $request->name;
        $role->syncPermissions($request->permissions);
        $role->save();

        return back()->with(['success'=>'Role has been updated successfully']);
        // return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        RoleTranslation::where('role_id',$id)->delete();
        Role::destroy($id);
        return redirect()->route('roles.index')->with(['success'=>tran('Role has been deleted successfully')]);
    }

    public function add_permission(Request $request)
    {
        $permission = Permission::create(['name' => $request->name, 'section'=> $request->parent]);
        return redirect()->route('roles.index');
    }
    public function create_admin_permissions(){
        
    }
}
