<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\GroupModuleDataTable;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\MY_Controller;
use Illuminate\Http\Request;
use App\Http\Traits\DataTrait;
use Yajra\DataTables\DataTables;
use \stdClass;

use App\Models\Group_Module;

class GroupmoduleController extends BaseController
{
    use DataTrait;
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Group Module';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(GroupModuleDataTable $dataTable, Request $request)
    {
        return $dataTable->render('groupmodule.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groupmodule = new stdClass();
        $groupmodule->id = '';
        $groupmodule->group_module_name = '';
        return view('groupmodule.edit')->with('groupmodule', $groupmodule);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_module_name' => 'required|unique:group_module|max:255',
        ]);
        $group_module = $this->createSingleRecord(Group_Module::class, $request->all());
        $this->addToLog(request());
        return redirect()->route('groupmodule.index');
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
        $group_module = $this->getSigleRecord(Group_Module::class, $id);
        return view('groupmodule.edit')->with('groupmodule', $group_module);
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
        $group_module = $this->updateById(Group_Module::class, $id, $request->all());
        $this->addToLog($request);
        return redirect()->route('groupmodule.index');
    }

    public function destroy(Request $request)
    {
        $this->deleteById(Group_Module::class, $request->id);
        $this->addToLog(request());
        return response()->json(['message' => 'Delete Successfully!']);
    }
}
