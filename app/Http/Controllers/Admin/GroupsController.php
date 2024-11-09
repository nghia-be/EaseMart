<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\GroupDataTable;
use App\Http\Controllers\BaseController;
use App\Http\Traits\DataTrait;
use App\Models\Groups;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class GroupsController extends BaseController
{
    use DataTrait;

    public function __construct()
    {
        parent::__construct();
        $this->title = 'List Group';
    }

    public function index(GroupDataTable $dataTable, Request $request)
    {
        return $dataTable->render('groups.index');
    }


    public function edit($id = null)
    {
        // get view edit
        $data = parent::edit1();
        return view('groups.edit')->with($data);
    }
    public function save(){
        $request = request()->all();
        if (request()->isMethod("post")) {
            if (empty($request['id']))
                $this->createSingleRecord(Groups::class, $request);
            else {
                $data['group_name'] = $request['group_name'];
                $this->updateById(Groups::class, $request['id'], $data);
            }
            $this->addToLog(request());
        }
        return redirect()->route('groups.index');
    }

    public function destroy(Request $request)
    {
        $this->deleteById(Groups::class, $request->id);
        $this->addToLog(request());
        return response()->json(['message' => 'Delete Successfully!']);
    }
    public function getList(Request $request){
        if ($request->ajax()) {
            $data = Groups::class::query()->with('createdBy');
            $json = DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return view('layouts.button.action')->with(['row' => $row, 'module' => 'groups']);
                })
                ->editColumn('created_by',function($row){
                    return !empty($row->createdBy) ? $row->createdBy->email : '';
                })
                ->make(true);
            return $json;
        }

    }
}
