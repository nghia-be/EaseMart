<?php

namespace App\Http\Controllers\Report;

use App\DataTables\Hi_FPT\DauWauMauReportDataTable;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MY_Controller;
use App\Http\Traits\DataTrait;
use App\Services\NewsEventService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use \App\Models\DAU_Report;

class DauWauMauReportController extends BaseController
{
    //
    use DataTrait;
    public function __construct()
    {
        parent::__construct();
        $this->title = 'DAU - WAU - MAU report';
    }
    public function index(DauWauMauReportDataTable $dataTable, Request $request) {
        $to_date = (!empty($request->daterange)) ? $request->daterange : date('Y-m-d', strtotime('today midnight'));
        $selectedZones = $request->selectedZones;
        $selectedType = $request->selectedType;
        $limit = (int) $request->length ?? 10;
        $currentPage = $request->start == 0 ? 0 : ($request->start / $limit);
        $selectedDate = date('Y-m-d', strtotime('today midnight'));

        return $dataTable->with([
            'to_date'       => $to_date,
            'selectedZones' => $selectedZones,
            'selectedType'  => $selectedType,
        ])->render('report.dauwaumaureport');
    }
}
