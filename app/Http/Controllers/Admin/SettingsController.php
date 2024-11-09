<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Hi_FPT\SettingsDataTable;
use App\Http\Controllers\MY_Controller;
use Illuminate\Http\Request;
use App\Http\Traits\DataTrait;
use Yajra\DataTables\DataTables;
use \stdClass;
use Illuminate\Support\Str;

use App\Models\Settings;

class SettingsController extends MY_Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    use DataTrait;
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Settings';
        $this->model = $this->getModel('Settings');
    }

    public function index(SettingsDataTable $dataTable, Request $request){
         return $dataTable->render('settings.list2');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $setting = new stdClass();
        $setting->id = '';
        $setting->name = '';
        $setting->value = '[]';
        return view('settings.edit')->with('setting', $setting);
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
            'name'  => 'required|unique:settings|max:255',
            'value' => 'required|json'
        ]);
        $setting = $this->createSingleRecord($this->model, $request->all());
        $this->addToLog(request());
        return redirect()->route('settings.index');
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
        $setting = $this->getSigleRecord($this->model, $id);
        return view('settings.edit')->with('setting', $setting);
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
        $request = request();
        $validated = $request->validate([
            'name'  => 'required|max:255',
            'value' => 'required|json'
        ]);
        $setting = $this->updateById($this->model, $id, $request->all());
        $this->addToLog($request);
        return redirect()->route('settings.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->deleteById($this->model, $id);
        $this->addToLog(request());
        return redirect()->route('settings.index');
    }

    public function initDatatable(Request $request){
        if($request->ajax()){
            $data = $this->model::query();
            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                return view('layouts.button.action')->with(['row'=>$row,'module'=>'settings']);
            })
            ->rawColumns(['action'])
            ->make(true);
        }
    }
}
