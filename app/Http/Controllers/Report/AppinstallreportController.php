<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\MY_Controller;
use App\Http\Traits\DataTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use \stdClass;

use Yajra\DataTables\DataTables;

use App\Models\App_install_logs;

use App\Exports\ExcelExport;
use Excel;
use File;

class AppinstallreportController extends BaseController
{
    //
    use DataTrait;

    public function __construct()
    {
        parent::__construct();
        $this->title = 'Count Install app';
        $this->model = new App_install_logs();
    }

    public function index(Request $request)
    {
        return view('report.appinstallreport');
    }

    public function initDatatableByDate(Request $request) {
        if($request->ajax()) {
            $response = $this->model->selectRaw('*, JSON_EXTRACT(`data`, "$.list_data") AS `list_data`')->whereBetween('date_report', [date('Y-m-d H:i:s', strtotime($request->from . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to . ' 23:59:59'))])->get()->toArray();
            return DataTables::of($response)
            ->addIndexColumn()
            ->make(true);
        }
    }

    public function initDatatableByWeek(Request $request){
        if($request->ajax()) {
            $response = $this->model->selectRaw('date_report, WEEK(date_report, 1) AS week_number, JSON_EXTRACT(`data`, "$.list_data") AS list_data')
                                    ->whereBetween('date_report', [date('Y-m-d H:i:s', strtotime($request->from . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to . ' 23:59:59'))])
                                    ->get()
                                    ->groupBy('week_number');
            return DataTables::of($response)
            ->addIndexColumn()
            ->make(true);
        }
    }

    public function initDatatableByMonth(Request $request){
        if($request->ajax()) {
            $response = $this->model->selectRaw('date_report, MONTH(date_report) AS month_number, JSON_EXTRACT(`data`, "$.list_data") AS list_data')
                                    ->whereBetween('date_report', [date('Y-m-d H:i:s', strtotime($request->from . ' 00:00:00')), date('Y-m-d H:i:s', strtotime($request->to . ' 23:59:59'))])
                                    ->get()
                                    ->groupBy('month_number');
            return DataTables::of($response)
            ->addIndexColumn()
            ->make(true);
        }
    }

    public function initDatatable(Request $request){
        if($request->ajax()) {
            $response = $this->model->get();
            return DataTables::of($response)
            ->addIndexColumn()
            ->make(true);
        }
    }

    public function export(Request $request) {
        try {
            $dataReport = Excel::raw(new ExcelExport([$request->all()], 'App\Exports\ExportAppInstall'), \Maatwebsite\Excel\Excel::XLSX);
            $response =  [
                'name' => "Pending-Task-List.xlsx",
                'file' => "data:application/vnd.ms-excel;base64,".base64_encode($dataReport)
            ];
        }
        catch(Exception $e) {
            // var_dump($e->getMessage());
            $response = [
                'message'   => $e->getMessage(),
                'name'      => '',
                'file'      => null
            ];
        }

        return response()->json($response);
    }
}
