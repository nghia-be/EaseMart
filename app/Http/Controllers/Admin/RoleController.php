<?php

namespace App\Http\Controllers\Admin;


use App\DataTables\Admin\RolesDataTable;
use App\Models\Modules;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\BaseController;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RoleController extends BaseController
{

    function __construct()
    {
        parent::__construct();
        $this->title = 'Quản lí phân quyền';
    }

    public function index(RolesDataTable $dataTable, Request $request)
    {
        return $dataTable->render('roles.index2');
    }

    public function create()
    {
        $permission = [];
        $abc = '';
        $per = Permission::get();
        foreach ($per as $value) {
            $sub = explode('-', $value->name);
            if ($abc != $sub[0] && !empty($sub[1])) {
                $modules = Modules::select('module_name')
                    ->where('uri', strtolower(preg_replace('/(?<!^)([A-Z])/', '-$1',$sub[0])))
                    ->first();
                $permission[$sub[0]]['name'] = !empty($modules) ? $modules->module_name : $sub[0];
                $permission[$sub[0]]['permission'][$value->id] = $sub[1];
            }
        }
        return view('roles.create', compact('permission'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permissions' => 'required'
        ]);
        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permissions'));

        return redirect()->intended('/role')
            ->with(['status'=>'success', 'html'=>'Role created successfully']);
    }

    public function show($id)
    {
        return redirect()->route('roles.index');
    }

    public function edit($id)
    {
        $role = Role::find($id);
        if ($role->name == 'Super Admin') {
            $notification = array(
                'status' => 'danger',
                'html' => "You have no permission for edit this role"
            );
            return redirect()->route('role.index')->with($notification);
        }

        $permission = [];
        $abc = '';
        $per = Permission::get();
        foreach ($per as $value) {
            $sub = explode('-', $value->name);
            if ($abc != $sub[0] && !empty($sub[1])) {
                $modules = Modules::select('module_name')
                    ->where('uri', strtolower(preg_replace('/(?<!^)([A-Z])/', '-$1',$sub[0])))
                    ->first();
                $permission[$sub[0]]['name'] = !empty($modules) ? $modules->module_name : $sub[0];
                $permission[$sub[0]]['permission'][$value->id] = $sub[1];
            }
        }
        $rolePermissions = DB::table("role_has_permissions")
            ->where("role_id", $id)
            ->pluck('permission_id')
            ->toArray();
        return view('roles.edit2', compact('role', 'permission', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('roles', 'name')->ignore($id)
            ],
            'permissions' => 'required'
        ]);
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permissions'));

        return redirect()->intended('/role')
            ->with(['status'=>'success', 'html'=>'Role updated successfully']);
    }

    public function destroy(Request $request)
    {
        $unit = Role::findOrFail($request->id);
        $unit->delete();
        $this->addToLog(request());
        return response()->json(['message' => 'Xóa thành công!']);
    }
}
