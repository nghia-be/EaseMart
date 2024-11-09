<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use stdClass;

class HelpRequestService
{
    private $clientKey;
    private $secretKey;
    private $token;
    private $baseUrl;
    private $version;
    private $listMethod;
    public function __construct()
    {
        $api_config         = config('configDomain.DOMAIN_REPORT.' . env('APP_ENV'));
        $this->baseUrl      = $api_config['URL'];
        $this->version      = $api_config['VERSION'];
        $this->clientKey    = $api_config['CLIENT_KEY'];
        $this->secretKey    = $api_config['SECRET_KEY'];
        $this->token        = md5($this->clientKey . "::" . $this->secretKey . date("Y-d-m"));
        $this->listMethod = config('configMethod.DOMAIN_REPORT');
    }

    public function getListReportByContract($contract_info)
    {
        $url = $this->baseUrl . $this->version . '/' . $this->listMethod['GET_LIST_REPORT'];
        $postParam = [
            'contractId' => $contract_info->Id,
            'customerId' => 555,
            'listContractHiFPT' => array(
                [
                    'contractNo' => $contract_info->Contract,
                    'contractId' => $contract_info->Id,
                    'customerIsActive' => 1
                ]
            )
        ];
        $response = sendRequest($url, $postParam, $this->token);
        return $response;
    }

    public function closeRequestByListReportId($listReportId = array())
    {
        // $url = $this->baseUrl . $this->version .'/'. $this->listMethod['CLOSE_REQUEST_BY_REPORT_ID'];
        $url = $this->baseUrl . 'report-local' . '/' . $this->listMethod['CLOSE_REQUEST_BY_REPORT_ID'];
        $listReport = [];
        if (empty($listReportId)) {
            return false;
        }
        foreach ($listReportId as $reportId) {
            $report_info  = new stdClass();
            $report_info->reportId = $reportId;
            $listReport[] = $report_info;
        }
        $postParam = [
            'listReportId' => $listReport
        ];
        $response  = sendRequest($url, $postParam, $this->token);
        return $response;
    }

    public function updateEmployeeByContract($contract_info)
    {
        $url = $this->baseUrl . 'report-local' . '/' . $this->listMethod['MY_UPDATE_EMPLOYEE'];
        $random_checklist_id = '4444' . random_int(10000, 99999);
        $postParam = [
            [
                "GroupId" => "TIN11.KHUONGDV",
                "Contract" => $contract_info->Id,
                "IdCheckList" => $random_checklist_id,
                "contractNo" => $contract_info->Contract,
                "employeeCode" => "00069708",
                "checkListType" => 2,
                "checkListFrom" => 2,
                "Time" => " ; " . date('d-m-y')
            ]
        ];
        $response =  sendRequest($url, $postParam, $this->token);
        $result = [
            'response'=>$response,
            'random_checklist_id'=>$random_checklist_id
        ];
        return $result;
    }
    public function splitCheckListValueRedis($value){
        $checklist = new stdClass;
        $checklist->id = substr($value, 0, 9);
        $checklist->contract = substr($value,10);
        return $checklist;
    }
    public function completeChecklist($checkId)
    {
        $url = $this->baseUrl . 'report-local' . '/' . $this->listMethod['MY_UPDATE_COMPLETE_CHECKLIST'];
        $postParam = [
            [
                "idCheckList" =>$checkId,
                "checkListType" => 2,
                "code" => 0
            ]
        ];
        $response =  sendRequest($url, $postParam, $this->token);
        $keyName = config('constants.REDIS_KEY.LIST_CHECKLIST_ID');
        if (Redis::exists($keyName)) {
            $data = unserialize(Redis::get($keyName));

            $find_check_id = array_search($checkId, array_column($data, 'ID'));
            if($find_check_id !== false){
                unset($data[$find_check_id]);
                Redis::set($keyName, serialize(array_values($data)));
            }
        }
        return $response;
    }
}
