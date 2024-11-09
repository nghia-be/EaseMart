<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\UserDataTable;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\MY_Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Traits\DataTrait;
use App\Models\Modules;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class UserController extends BaseController
{
    use DataTrait;
    public $model;
    public function __construct()
    {
        parent::__construct();
        $this->title = 'List User';
        $this->model = new User();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UserDataTable $dataTable, Request $request)
    {
        return $dataTable->render('user.index');
    }

    public function create()
    {
        $role = Role::get();
        return view('user.create')->with(['role'=>$role]);
    }

    public function store(UserStoreRequest $request)
    {
        $request->merge([
            'password' => Hash::make($request->password),
            'created_by' => auth()->user()->id
        ]);
        $user = User::create($request->only(['name', 'email', 'password', 'created_by']));
        if (!empty($request->administrator)) {
            $role = Role::firstOrCreate(['name' => 'Admin']);
        } else {
            $role = Role::firstOrCreate(['name' => $request->role]);
        }
        $user->syncRoles($role);
        return redirect()->route('user.index')->with(['status'=>'success', 'html' => 'Thành công']);
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

    public function edit($id)
    {
        $role = Role::get();
        $user = User::find($id);
        return view('user.edit')->with(['user'=>$user, 'role'=>$role]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'password' => 'nullable|min:1',
            'password_confirmation' => 'nullable|same:password',
        ]);
        $request->request->remove('password_confirmation');
        if($request->password != null){
            $request->merge([
                'password' => Hash::make($request->password),
                'created_by' => auth()->user()->id
            ]);
        }else{
            $request->request->remove('password');
            $request->request->remove('password_confirmation');
        }
        $user = User::find($id);
        $user->update($request->only(['name', 'email', 'password','role_id', 'created_by']));
        if (!empty($request->administrator)) {
            $role = Role::firstOrCreate(['name' => 'Admin']);
        } else {
            $role = Role::firstOrCreate(['name' => $request->role]);
        }
        $user->syncRoles($role);
        $this->addToLog($request);
        return redirect()->route('user.index')->with(['status'=>'success', 'html' => 'Thành công']);
    }

    public function login(Request $request)
    {
        auth()->loginUsingId($request->id);
        return redirect()->intended('/');
    }

   public function destroy(Request $request)
   {
       $this->deleteById($this->model, $request->id);
       $this->addToLog(request());
       return response()->json(['message' => 'Delete Successfully!']);
   }
}
