<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\RoleDataTable;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\MY_Controller;
use App\Http\Traits\DataTrait;
use App\Models\Acl_Roles;
use App\Models\Modules;
use App\Models\Roles;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use stdClass;

class RolesController extends MY_Controller
{
    use DataTrait;
    protected $module_name = 'Roles';
    protected $model_name = "Roles";

    public function __construct()
    {
        parent::__construct();
        $this->model = new Roles();
    }

    public function index(RoleDataTable $dataTable, Request $request)
    {
        return $dataTable->render('roles.index');
    }

    public function edit()
    {
        // get view edit
        $data = parent::edit1();
        $data['modules'] = Modules::query()->get();
        if(isset($data['data']['id'])){
            $data['acls'] = Roles::find($data['data']['id'])->acls;
        }
        return view('roles.edit')->with($data);
    }
    public function save()
    {
        $model_groups = $this->getModel('roles');
        $request = request()->all();
        if (request()->isMethod("post")) {
            DB::transaction(function () use ($request, $model_groups) {
                $listModuleAclInput = [];
                $role = new stdClass();
                if (empty($request['id']))
                    $role = $this->createSingleRecord($model_groups, $request);
                else {
                    $data['role_name'] = $request['role_name'];
                    $this->updateById($model_groups, $request['id'], $data);
                    $role->id = $request['id'];
                }
                //create and update permission
                if (isset($request['module_id'])) {
                    $arrayDataAcl = [];
                    foreach ($request['module_id'] as $key => $val) {
                        $dataAcl = [];
                        $dataAcl['role_id'] = $role->id;
                        $dataAcl['module_id'] = $val;
                        $dataAcl['view'] = $request['view'][$key];
                        $dataAcl['delete'] = $request['delete'][$key];
                        $dataAcl['create'] = $request['create'][$key];
                        $dataAcl['update'] = $request['update'][$key];
                        $dataAcl['deleted_at'] = NULL;
                        $arrayDataAcl[] = $dataAcl;
                        $listModuleAclInput[] = $val;
                    }
                    DB::table('acl_roles')->upsert($arrayDataAcl, ['role_id', 'module_id'], ['view', 'delete', 'create', 'update', 'deleted_at']);
                    // dd($arrayDataAcl);
                }
                Acl_Roles::deleteEmptyAclRole($role->id, $listModuleAclInput);
                // update redis data;
                $getModuleData = (new Modules())->getModulesGroupByParent($request['id']);
                $keyName = config('constants.REDIS_KEY.MODULE_BY_ROLE_ID').$request['id']; // redis key: acl role module
                Redis::set($keyName, serialize($getModuleData));
                $this->addToLog(request());
            });
        }
        return redirect()->route('roles.index');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $this->deleteById($this->model, $request->id);
        $this->addToLog(request());
        return response()->json(['message' => 'Delete Successfully!']);
    }
    public function getList(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->model::query()->with('createdBy');
            $json =  DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return view('layouts.button.action')->with(['row' => $row, 'module' => 'roles']);
                })
                ->editColumn('created_by',function($row){
                    return !empty($row->createdBy) ? $row->createdBy->email : '';
                })
                ->make(true);
            return $json;
        }
    }
}
