<?php

namespace App\Http\Controllers\Report;

use App\DataTables\Hi_FPT\LogSupportCodeDatatable;
use App\DataTables\Hi_FPT\SuportCodeDataTable;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MY_Controller;
use App\Http\Traits\DataTrait;
use App\Services\HdiCustomer;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Models\Settings;
use App\Models\Contracts;
use App\Models\Customers;
use App\Models\Insert_Service_Internet;
use App\Models\Orders;
use App\Models\Payment_Orders;
use App\Models\Saleplatform_Shopping_Orders;
use App\Models\Upgrade_Service_Internet;
use App\Models\Customer_Locations;
use App\Models\Customer_Contract;
use App\Models\Active_Net;
use Illuminate\Support\Facades\DB;

class ReportTrackingCusBehaviorMonthlyController extends MY_Controller
{
    //
    use DataTrait;
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Báo cáo hành vi khách hàng theo tháng';
        $this->model = $this->getModel('Support_code_reset_logs');
    }

    public function index(Request $request) {
        $chisobaocao = Settings::where('name', 'ChiSoBaoCaoThangHiFPT')->get();
        $from2 = $request->show_from;
        $to2 = $request->show_to;

        if(empty($from2)) {
            // $from2 = date('Y-m-01 00:00:00', strtotime('now'));
            $from2 = '2022-11-01 00:00:00';
        }

        if(empty($to2)) {
            // $to2 = date('Y-m-t 23:59:59', strtotime('now'));
            $to2 = '2022-11-30 23:59:59';
        }

        $from1 = date('Y-m-01 00:00:00', strtotime($from2 . ' last month'));
        $to1 = date('Y-m-d 23:59:59', strtotime($to2 . ' last day last month'));
        // dd($from1);
        // return $dataTable->with([
        //     'supportCode'   => $request->supportCode,
        //     'start'         => $request->start,
        //     'length'        => $request->length,
        //     'order'         => $request->order,
        //     'columns'       => $request->columns,
        //     ])->render('support_code.index');

        return view('report.trackingcusbehaviormonthly')->with([
            'filter'            => [
                'chisobaocao'   => (!empty($request->chisobaocao)) ? $request->chisobaocao : [],
            ],
            'chisobaocaos'      => (!empty($chisobaocao[0]['value'])) ? json_decode($chisobaocao[0]['value'], true) : [],
            // 'activebymonth'     => $this->getDataActiveMonthly($from1, $to1, $from2, $to2)
        ]);
    }

    // public function activeNet() {

    // }

    public function activeNet(Request $request) {
        // dd($request->all());
        $from_month = $request->from_month;
        // $from2 = date('Y-m-01 H:i:s', strtotime($from_month . '-01 00:00:00'));
        $from2 = '2016-09-13 00:00:00';
        $to2 = date('Y-m-t H:i:s', strtotime($from_month . '-01 23:59:59'));

        $from1 = date('Y-m-01 00:00:00', strtotime($from2 . ' last month'));
        $to1 = date('Y-m-t 23:59:59', strtotime($to2 . ' last day last month'));

        $contracts = Contracts::selectRaw("location_zone, COUNT(contract_no) AS active")
                            ->whereNotIn('location_zone', ['CAM'])
                            ->whereBetween('date_created', [$from2, $to2])
                            ->groupBy('location_zone')
                            ->orderBy('location_zone')
                            ->get()
                            ->toArray();

        $data = array_column($contracts, null, 'location_zone');

        $active_net_raw = Active_Net::selectRaw('zone, SUM(count) AS active_net')->whereBetween('date_created', [$from2, $to2])->groupBy('zone')->get()->toArray();
        $active_net = array_column($active_net_raw, 'active_net', 'zone');

        foreach($data as $key => &$value) {
            $value['active_net'] = (!empty($active_net[$key])) ? $active_net[$key] : 0;
        }
        return ["data" => $data, "time" => ["from" => 'T' . date('m-Y', strtotime($from1)), "to" => 'T' . date('m-Y', strtotime($to2))]];
    }

    public function getDataActiveMonthly(Request $request) {
        // $from2 = $request->show_from;
        // $to2 = $request->show_to;

        $from_month = $request->from_month;
        $from2 = date('Y-m-01 H:i:s', strtotime($from_month . '-01 00:00:00'));
        $to2 = date('Y-m-t H:i:s', strtotime($from_month . '-01 23:59:59'));

        $from1 = date('Y-m-01 00:00:00', strtotime($from2 . ' last month'));
        $to1 = date('Y-m-t 23:59:59', strtotime($to2 . ' last day last month'));

        $data = Contracts::selectRaw("location_zone,
                                     SUM(IF(DATE(date_created) BETWEEN '" . $from1 . "' AND '" . $to1 . "', 1, 0)) AS count_last_month,
                                     SUM(IF(DATE(date_created) BETWEEN '" . $from2 . "' AND '" . $to2 . "', 1, 0)) AS count_this_month")
                        ->whereBetween('date_created', [$from1, $to2])
                        ->groupBy('location_zone')
                        ->orderBy('location_zone')
                        ->get()->toArray();

        $data_no_contract = json_decode(json_encode(Customers::count_no_contract($from1, $to1, $from2, $to2)), true);
        array_push($data, [
            "location_zone"     => "Tài khoản không hợp đồng", 
            "count_last_month"  => (isset($data_no_contract[0]['count_last_month']) ? $data_no_contract[0]['count_last_month'] : 0),
            "count_this_month"  => (isset($data_no_contract[0]['count_this_month']) ? $data_no_contract[0]['count_this_month'] : 0),
        ]);
        
        return ["data" => $data, "data_no_contract" => $data_no_contract, "time" => ["from" => 'T' . date('m-Y', strtotime($from1)), "to" => 'T' . date('m-Y', strtotime($to2))]];
    }

    public function getDataActivePttbMonthly(Request $request) {
        $from_month = $request->from_month;
        $from2 = date('Y-m-01 H:i:s', strtotime($from_month . '-01 00:00:00'));
        $to2 = date('Y-m-t H:i:s', strtotime($from_month . '-01 23:59:59'));
        $data = [];

        $data_new_raw = Contracts::selectRaw("location_zone, COUNT(contract_no) AS ptm")
                            ->whereBetween('date_created', [$from2, $to2])
                            ->whereBetween('first_access', [$from2, $to2])
                            ->groupBy('location_zone')
                            ->orderBy('location_zone')
                            ->get()
                            ->toArray();
        $data = array_column($data_new_raw, null, 'location_zone');
        
        $data_bao_tri_raw = Contracts::selectRaw("location_zone, COUNT(contract_no) AS bt")
                            ->whereBetween('date_created', [$from2, $to2])
                            ->whereNotBetween('first_access', [$from2, $to2])
                            ->groupBy('location_zone')
                            ->orderBy('location_zone')
                            ->get()
                            ->toArray();

        $data_bao_tri = array_column($data_bao_tri_raw, 'bt', 'location_zone');

        $active_net_raw = Active_Net::selectRaw('zone, SUM(count) AS active_net')->whereBetween('date_created', [$from2, $to2])->groupBy('zone')->get()->toArray();
        $active_net = array_column($active_net_raw, 'active_net', 'zone');
        // dd($active_net_raw);

        foreach($data as $key => &$value) {
            $value['bt'] = (!empty($data_bao_tri[$key])) ? $data_bao_tri[$key] : 0;
            $value['active_net'] = (!empty($active_net[$key])) ? $active_net[$key] : 0;
        }
        return ["data" => $data, "time" => ["from" => 'T' . date('m-Y', strtotime($from2)), "to" => 'T' . date('m-Y', strtotime($to2))]];
    }

    public function paymentMonthly(Request $request) {
        $from_month = $request->from_month;
        $from2 = date('Y-m-01 H:i:s', strtotime($from_month . '-01 00:00:00'));
        $to2 = date('Y-m-t H:i:s', strtotime($from_month . '-01 23:59:59'));

        $from1 = date('Y-m-01 00:00:00', strtotime($from2 . ' last month'));
        $to1 = date('Y-m-t 23:59:59', strtotime($to2 . ' last day last month'));

        $data = Payment_Orders::selectRaw("o.location_zone AS location_zone, SUM(IF(DATE(view_payment_orders.date_created) BETWEEN '$from1' AND '$to1', 1, 0)) AS count_last_month, SUM(IF(DATE(view_payment_orders.date_created) BETWEEN '$from2' AND '$to2', 1, 0)) AS count_this_month, SUM(IF(DATE(view_payment_orders.date_created) BETWEEN '$from1' AND '$to1', view_payment_orders.amount, 0)) AS amount_last_month, SUM(IF(DATE(view_payment_orders.date_created) BETWEEN '$from2' AND '$to2', view_payment_orders.amount, 0)) AS amount_this_month")
                      ->join('view_orders AS o', DB::raw("CONCAT_WS('_', view_payment_orders.payment_type, view_payment_orders.version, view_payment_orders.order_id)"), "=", "o.order_id_merchant")
                      ->whereIn('view_payment_orders.payment_type', ['DKOLMB', 'DKOLMN', 'FPTPLAY', 'FSHMB', 'FSHMN', 'FTELMB', 'FTELMN', 'PAYWF', 'UTMB', 'UTMN'])
                      ->where('payment_provider_status', 'SUCCESS')
                      ->whereBetween('view_payment_orders.date_created', [$from1, $to2])
                      ->groupBy('location_zone')
                      ->orderBy('location_zone')
                      ->get()
                      ->toArray();
        return ["data" => $data, "time" => ["from" => 'T' . date('m-Y', strtotime($from1)), "to" => 'T' . date('m-Y', strtotime($to2))]];
    }

    public function log(LogSupportCodeDatatable $dataTable) {
        return $dataTable->render('support_code.log');
    }

    public function openSupportCode(Request $request) {
        $log_data = [];
        $hdiCustomer = new HdiCustomer();
        $api_result = $hdiCustomer->resetDeviceLockByCode(['supportCode' => $request->supportCode]);

        if(isset($api_result['statusCode']) && $api_result['statusCode'] == 0) {
            if(!empty($api_result['data'])) {
                $log_data = $api_result['data'];
                $log_data['code_created_at'] = date('Y-m-d H:i:s', strtotime(@$api_result['data']['created_at']));
                $log_data['code_updated_at'] = date('Y-m-d H:i:s', strtotime(@$api_result['data']['updated_at']));
                $log_data['last_updated'] = date('Y-m-d H:i:s', strtotime(@$api_result['data']['last_updated']));
                $log_data['api_result'] = json_encode($api_result);
                $log_data['created_by'] = Auth::check() ? Auth::user()->id : 0;
                $log_data['note'] = $request->note;
                $log_data['created_at'] = (!empty($request->created_at)) ? $request->created_at : date('Y-m-d H:i:s', strtotime('now'));
                $log_data['updated_at'] = (!empty($request->created_at)) ? $request->created_at : date('Y-m-d H:i:s', strtotime('now'));
            }
        }

        $log_data['statusCode'] = @$api_result['statusCode'];
        $log_data['message'] = @$api_result['message'];
        $this->model->create($log_data);
        if(isset($api_result['statusCode']) && $api_result['statusCode'] == 0) {
            return redirect()->back()->withSuccess($api_result['message']);
        }
        else {
            return redirect()->back()->withErrors($api_result['message']);
        }
    }

    public function newServiceRegister(Request $request) {
        $from_month = $request->from_month;
        $from2 = date('Y-m-01 H:i:s', strtotime($from_month . '-01 00:00:00'));
        $to2 = date('Y-m-t H:i:s', strtotime($from_month . '-01 23:59:59'));

        $from1 = date('Y-m-01 00:00:00', strtotime($from2 . ' last month'));
        $to1 = date('Y-m-t 23:59:59', strtotime($to2 . ' last day last month'));

        $data = Saleplatform_Shopping_Orders::selectRaw("service_key, location_zone, SUM(IF(DATE(inside_order_create_time) BETWEEN '$from1' AND '$to1', 1, 0)) AS count_last_month, SUM(IF(DATE(inside_order_create_time) BETWEEN '$from2' AND '$to2', 1, 0)) AS count_this_month")
                                            ->whereIn('order_status', ['ECONTRACT_SIGNED', 'ORDERED_PENDING', 'ORDERED_SUCCESSFUL', 'WAIT_ECONTRACT_SIGNED', 'ORDER_COMPLETED'])
                                            ->whereBetween('inside_order_create_time', [$from1, $to2])
                                            ->groupBy(['service_key', 'location_zone'])
                                            ->orderBy('location_zone')
                                            ->get()->toArray();

        return ["data" => $data, "time" => ["from" => 'T' . date('m-Y', strtotime($from1)), "to" => 'T' . date('m-Y', strtotime($to2))]];
    }

    public function upgradeServiceRegister(Request $request) {
        $from_month = $request->from_month;
        $from2 = date('Y-m-01 H:i:s', strtotime($from_month . '-01 00:00:00'));
        $to2 = date('Y-m-t H:i:s', strtotime($from_month . '-01 23:59:59'));

        $from1 = date('Y-m-01 00:00:00', strtotime($from2 . ' last month'));
        $to1 = date('Y-m-t 23:59:59', strtotime($to2 . ' last day last month'));
        $data = [];
        $raw = Upgrade_Service_Internet::with('contract')
                                        ->whereBetween('upgrade_service_internet.t_create', [$from1, $to2])
                                        ->get()
                                        ->groupBy(['service_name_new', 'contract.location_zone', function($item) {
                                            return date('m-Y', strtotime($item['t_create']));
                                        }]);
                                        // ->orderBy('location_zone')
                                        // ->select("service_name_new, contract.location_zone, SUM(IF(DATE(upgrade_service_internet.t_create) BETWEEN '$from1' AND '$to1', 1, 0)) AS count_last_month, SUM(IF(DATE(upgrade_service_internet.t_create) BETWEEN '$from2' AND '$to2', 1, 0)) AS count_this_month")
                                        // ->all();
                                        // ->toArray();
        foreach($raw as $key => $value) {
            foreach($value as $valueKey => $valueValue) {
                $row = [];
                $row['location_zone'] = $valueKey;
                $row['service_name_new'] = $key;
                foreach($valueValue as $valueKeyMonth => $valueValueMonth) {
                    if(date('m-Y', strtotime($from1)) == $valueKeyMonth) {
                        $row['count_last_month'] = count($valueValueMonth);
                    }
                    else {
                        $row['count_this_month'] = count($valueValueMonth);
                    }  
                }
                array_push($data, $row);
            }
            
            // echo($key);
            // echo '<pre>';
            // print_r($value->toArray());
            // echo '</pre>';
        }
        $result = collect($data)->sortBy('location_zone');
        // dd($result);
        return ["data" => $result->values()->all(), "time" => ["from" => 'T' . date('m-Y', strtotime($from1)), "to" => 'T' . date('m-Y', strtotime($to2))]];
    }

    public function chatData(Request $request) {
        $from_month = $request->from_month;
        $from2 = date('Y-m-01', strtotime($from_month . '-01 00:00:00'));
        $to2 = date('Y-m-t', strtotime($from_month . '-01 23:59:59'));
        $from1 = date('Y-m-01 00:00:00', strtotime($from2 . ' last month'));
        $to1 = date('Y-m-t 23:59:59', strtotime($to2 . ' last day last month'));

        $sub_url = 'hichat/hifpt/provider/report/count-conversation';
        $url = config('configDomain.DOMAIN_HIFPT.' . env('APP_ENV') . '.URL') . $sub_url;
        $params2 = [
            'type'      => 'customer',
            'channel'   => 'ftel',
            'fromDate'  => $from2,
            'toDate'    => $to2
        ];

        $params1 = [
            'type'      => 'customer',
            'channel'   => 'ftel',
            'fromDate'  => $from1,
            'toDate'    => $to1
        ];

        $data1 = json_decode(json_encode(sendRequest($url, $params1, null, [], 'POST', true)), true);
        // dd($data1);
        $data2 = json_decode(json_encode(sendRequest($url, $params2, null, [], 'POST', true)), true);
        return ["data" => ['function' => 'Chat FTEL', 'count_this_month' => (!empty($data2['data'][0]['n'])) ? $data2['data'][0]['n'] : 0, 'count_last_month' => (!empty($data1['data'][0]['n'])) ? $data1['data'][0]['n'] : 0, "time" => ["from" => 'Tháng ' . date('m-Y', strtotime($from1)), "to" => 'Tháng ' . date('m-Y', strtotime($to2))]]];
    }
}
