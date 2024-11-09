<?php

namespace App\Http\Controllers\Hdi;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\MY_Controller;
use App\Services\ContractService;
use App\Services\HelpRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;;

class ChecklistmanageController extends BaseController
{
    //
    public function __construct()
    {
        $this->title = 'Check List';
        parent::__construct();
    }
    public function index()
    {
        $listCheckList = $this->getListCheckList();
        return view('checklist.index')->with(['list_checklist_id' => $listCheckList]);
    }

    public function sendStaff(Request $request)
    {
        $request->validate([
            'contractNo' => 'required'
        ]);
        $this->addToLog(request());
        $contractService = new ContractService();
        $helpReqeustService = new HelpRequestService();
        $contract_info_response = $contractService->getContractInfo($request->contractNo); // call api get contract info
        if (empty($contract_info_response->data)) {
            return redirect()->back()->withErrors(['error' => "Hợp đồng không tồn tại!"]);
        }
        $contract_info = $contract_info_response->data[0];
        $list_report_response = $helpReqeustService->updateEmployeeByContract($contract_info); // call api get list report by contract
        if ($list_report_response['response']->statusCode != 0) {
            return redirect('/checklistmanage')->withErrors(['error' => $list_report_response['response']->message]);
        }
        $keyName = config('constants.REDIS_KEY.LIST_CHECKLIST_ID');
        if (Redis::exists($keyName)) {
            $data = unserialize(Redis::get($keyName));
            $data[] = (object)[
                'ID' => $list_report_response['random_checklist_id'],
                'HD' => $contract_info->Contract
            ];
        } else {
            $data = array((object)[
                    'ID' => $list_report_response['random_checklist_id'],
                    'HD' => $contract_info->Contract
                ]
            );
        }
        Redis::set($keyName, serialize($data));
        // continue
        return redirect()->route('checklistmanage.index')->withSuccess(['success' => 'success']);
    }

    public function completeChecklist(Request $request)
    {
        if (!$request->ajax()) {
            $request->validate([
                'checkListId' => 'required'
            ]);
            $helpReqeustService = new HelpRequestService();
            $completeChecklist_response = $helpReqeustService->completeChecklist($request->checkListId);
            if ($completeChecklist_response->statusCode != 0) {
                return redirect()->route('checklistmanage.index')->withErrors(['error' => $completeChecklist_response->message]);
            }
            $this->addToLog($request);
            return redirect()->route('checklistmanage.index')->withSuccess(['success' => 'success']);
        }
    }
    private function getListCheckList()
    {
        $keyName = config('constants.REDIS_KEY.LIST_CHECKLIST_ID');
        $list=[];
        if (Redis::exists($keyName)) {
            $list = unserialize(Redis::get($keyName));
        }
        return $list;
    }
}
