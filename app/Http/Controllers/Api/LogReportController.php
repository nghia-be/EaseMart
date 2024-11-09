<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Log_Report;
use App\Models\Log_Report_Type;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class LogReportController extends Controller
{
    private $lang = 'vi';
    public function __construct(Request $request)
    {
        $this->lang = (isset($request->lang) && strtolower($request->lang) == 'en') ? 'en' : 'vi';
    }

    public function saveReport(Request $request)
    {
        $result = null;
        $statusObject = null;
        $msgData = null;
        try {
            //validate
            $params = $request->all();
            $rules = [
                'report_type_id' => 'required',
                'date_report' => 'required|date_format:Y-m-d',
                'data' => 'required|array',
                'data.list_column' => 'required',
                'data.list_data' => 'required',
                'source' => 'required',
            ];
            $validatetor = Validator::make($params, $rules);
            if ($validatetor->fails()) {
                $msgData = $validatetor->getMessageBag();
                throw new Exception('INVALID_INPUT');
            }
            extract($request->all());
            $listReportType = Log_Report_Type::get()->toArray();
            $is_report_type_id_valid = array_search($report_type_id, array_column($listReportType, 'id'));
            if($is_report_type_id_valid === false){
                $msgData = 'report type id not Found';
                throw new Exception('INVALID_INPUT');
            }
            //end validate
            $createParams = [
                'report_type_id' => $report_type_id,
                'date_report' => $date_report,
                'data' => json_encode($data),
                'source' => $source
            ];
            Log_Report::create($createParams);
            $result = 'saved';
        } catch (Exception $e) {
            $result = $msgData;
            $statusObject = buildStatusObject($e->getMessage());
        }
        return printJson($result, $statusObject, $this->lang);
    }

}
