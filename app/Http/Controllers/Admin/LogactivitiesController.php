<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\LogDataTable;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\MY_Controller;
use App\Http\Traits\DataTrait;
use App\Models\Log_activities;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class LogactivitiesController extends BaseController
{
    //
    use DataTrait;
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Log Activites';
    }
    public function index(LogDataTable $dataTable, Request $request)
    {
        return $dataTable->render('log.index');
    }

    public function destroy($id)
    {
        $this->deleteById(Log_activities::class, $id);
        return redirect()->back();
    }

    public function clearLog(Request $request)
    {
        $request->validate([
            'clear_log_option' => 'required'
        ]);
        DB::transaction(function () use ($request) {
            $log = new Log_activities();
            $log->clearLog($request->clear_log_option);
        });
        return redirect()->back()->with(['success' => 'Success', 'html' =>'Delete Successfully!']);
    }
}
