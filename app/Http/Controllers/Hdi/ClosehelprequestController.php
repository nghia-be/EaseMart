<?php

namespace App\Http\Controllers\Hdi;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\MY_Controller;
use App\Services\ContractService;
use App\Services\HelpRequestService;
use GrahamCampbell\ResultType\Result;
use Illuminate\Http\Request;

use App\Models\Close_Helper_Request_Log;

class ClosehelprequestController extends BaseController
{
    //
    public function __construct()
    {
        $this->title = 'Close Request';
        parent::__construct();
    }
    public function index(Request $request)
    {
        if(!isset($request->contractNo)){
            return view('helprequest.index');
        };
        $resultData = $this->getListReportByContract($request);
        if(isset($resultData['error'])){
            return view('helprequest.index');
        };
        $data = [
            'result' => $resultData['data'],
            'contract' => $resultData['contract']
        ];
        return view('helprequest.index')->with(['data'=>$data]);
    }

    public function getListReportByContract(Request $request)
    {
        $this->addToLog($request);
        $result = [];
        $contractService = new ContractService();
        $helpReqeustService = new HelpRequestService();
        $contract_info_response = $contractService->getContractInfo($request->contractNo); // call api get contract info
        if(empty($contract_info_response->data)){
            $result['error'] = "Hợp đồng không tồn tại!";
        }else{
            $contract_info = $contract_info_response->data[0];
            $list_report_response = $helpReqeustService->getListReportByContract($contract_info); // call api get list report by contract
            if(empty($list_report_response->data)){
                $result['error'] = "Không có yêu cầu hỗ trợ nào!";
            }else{
                $result['data'] = $list_report_response->data;
                $result['contract'] = $request->contractNo;
            }
        }
        return $result;
    }

    public function closeRequest(Request $request)
    {
        if(!$request->ajax()){
            return false;
        }
        $request->validate([
            'report_id' =>'required'
        ]);

        $helpReqeustService = new HelpRequestService();
        $response = $helpReqeustService->closeRequestByListReportId([$request->report_id]);
        Close_Helper_Request_Log::create([
            'contract_no'   => $request['contract_no'],
            'report_id'     => $request['report_id'],
            'response'      => json_encode($response)
        ]);
        $this->addToLog($request);
        return true;
    }
}
