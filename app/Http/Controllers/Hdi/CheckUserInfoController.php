<?php

namespace App\Http\Controllers\Hdi;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\MY_Controller;
use App\Services\ContractService;
use Illuminate\Http\Request;
use SebastianBergmann\Environment\Console;

class CheckUserInfoController extends BaseController
{
    //
    public function __construct()
    {
        $this->title = 'Check User Info';
        parent::__construct();
    }

    public function index(Request $request){

        if(empty($request->input)){
            return view('check_user_info.index');
        };

        $resultData = $this->checkUserInfo($request);
        if(isset($resultData->error)){
            // return redirect()->route('checkuserinfo.index')->withErrors($resultData->error);
            return view('check_user_info.index');
        }
        return view('check_user_info.index')->with(['data'=>$resultData->data]);
    }
    public function checkUserInfo(Request $request)
    {
        $this->addToLog($request);
        $input = $this->validateInput($request->input);
        if ($this->checkInPutIsPhone($input)) {
            $result = $this->checkUserInfoByPhone($request->input);
        } else {
            $result = $this->checkUserInfoByContract($request->input);
        }
        return $result;
    }
    private function checkInPutIsPhone($input){
        return (is_numeric($input) && strlen($input)>8 && strlen($input) <= 11) ? true:false;
    }
    private function validateInput($input){
        $input = str_replace('(+84)', '0',$input);
        $input = str_replace('+84', '0',$input);
        $input = str_replace('0084', '0',$input);
        $input = str_replace(' ', '',$input);
        return $input;
    }
    private function checkUserInfoByContract($contractNo)
    {
        $result = (object)[];
        $contractService = new ContractService();
        $contract_info_response = $contractService->getContractInfo($contractNo); // call api get contract info
        if (empty($contract_info_response->data)) {
            $result->error = "Thất Bại!!";
        } else {
            $result = $contract_info_response;
        }
        return $result;
    }
    private function checkUserInfoByPhone($phoneNumber){
        $result = (object)[];
        $contractService = new ContractService();
        $contract_info_response = $contractService->getListcontractByPhone($phoneNumber); // call api get contract info
        if (empty($contract_info_response->data)) {
            $result->error = "Thất Bại!!";
        } else {
            $result = $contract_info_response;
        }
        return $result;
    }
}
