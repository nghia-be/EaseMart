<?php

namespace App\Http\Controllers\Report;

use App\DataTables\Hi_FPT\SaleReportDataMultiServiceDataTable;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MY_Controller;
use App\Http\Traits\DataTrait;
use App\Services\NewsEventService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;

use App\Models\Settings;
use App\Models\Customer_Locations;
use App\Models\Ftel_Branch;
use App\Models\Sale_Report_Data_Multi_Service;
use Illuminate\Support\Facades\DB;

use App\Exports\ExportArray;
use Maatwebsite\Excel\Facades\Excel;

class SaleReportDataMultiServiceController extends MY_Controller
{
    //
    use DataTrait;
    protected $module_name = 'Employees';
    protected $model_name = "Employees";
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Employees Manage';
        $this->model = $this->getModel('Sale_Report_Data_Multi_Service');
    }
    public function index(SaleReportDataMultiServiceDataTable $dataTable, Request $request) {
        $order_states = Settings::where('name', 'laptop_orders_order_state')->get();
        $payment_status = Settings::where('name', 'laptop_orders_payment_status')->get();
        $payment_methods = Settings::where('name', 'laptop_orders_payment_method')->get();
        $services = Settings::where('name', 'multi_service_service_settings')->get();

        $zone = Customer_Locations::select(['location_zone'])->groupBy('location_zone')->orderBy('location_zone')->get()->toArray();
        $branch_code = Customer_Locations::select(['customer_location_id', 'location_name_vi', 'location_zone', 'location_code'])->orderBy('customer_location_id')->get()->toArray();
        $ftel_branch = Ftel_Branch::orderBy('location_id')->get()->toArray();

        // $services = [
        //     ['key' => 'hdi', 'value' => 'Bảo hiểm HDI'],
        //     ['key' => 'air_condition', 'value' => 'Vệ sinh máy lạnh'],
        //     ['key' => 'it_service', 'value' => 'Dịch vụ FConnect'],
        //     ['key' => 'laptop', 'value' => 'ICT'],
        //     ['key' => 'houseware', 'value' => 'Gia dụng'],
        //     ['key' => 'vuanem', 'value' => 'Vua nệm'],
        //     ['key' => 'gas', 'value' => 'Gas/Nước'],
        //     ['key' => 'ultrafast', 'value' => 'Ultra Fast'],
        //     ['key' => 'vietlott', 'value' => 'Vietlott'],
        // ];

        return $dataTable->with([
            'start'                             => $request->start,
            'length'                            => $request->length,
            'customer_phone'                    => $request->customer_phone,
            'from'                              => $request->show_from,
            'to'                                => $request->show_to,
            'service_type'                      => $request->service_type,
            'order_state'                       => $request->order_state,
            'payment_method'                    => $request->payment_method,
            'payment_status'                    => $request->payment_status,
            'zone'                              => $request->zone,
            'branch_code'                       => $request->branch_code,
            'ftel_branch'                       => $request->ftel_branch,
            'isAndServiceType'                  => $request->isAndServiceType,
            'columns'                           => $request->columns,
            ])->render('sale_report_data_multi_service.index', [
                'filter'                        => [
                    'customer_phone'            => @$request->customer_phone, 
                    'show_from'                 => @$request->show_from, 
                    'show_to'                   => @$request->show_to,
                    'service_type_selected'     => (!empty($request->service_type)) ? $request->service_type : [],
                    'order_state_selected'      => (!empty($request->order_state)) ? $request->order_state : [],
                    'zone_selected'             => (!empty($request->zone)) ? $request->zone : [],
                    'branch_code_selected'      => (!empty($request->branch_code)) ? $request->branch_code : [],
                    'ftel_branch_selected'      => (!empty($request->ftel_branch)) ? $request->ftel_branch : [],
                    'service_type_selected'     => (!empty($request->services)) ? $request->services : [],
                    'payment_method_selected'   => (!empty($request->payment_method)) ? $request->payment_method : [],
                    'order_state_selected'      => (!empty($request->order_state)) ? $request->order_state : [],
                    'payment_status_selected'   => (!empty($request->payment_status)) ? $request->payment_status : [],
                    'service_type_selected'     => (!empty($request->service_type)) ? $request->service_type : [],
                    'isAndServiceType'          => $request->isAndServiceType
                ],
                'order_state'                   => (!empty($order_states[0]['value'])) ? json_decode($order_states[0]['value'], true) : [],
                'zones'                         => @$zone,
                'branch_codes'                  => @$branch_code,
                'ftel_branchs'                  => @$ftel_branch,
                'service_types'                 => (!empty($services[0]['value'])) ? json_decode($services[0]['value'], true) : [],
                'payment_methods'               => (!empty($payment_methods[0]['value'])) ? json_decode($payment_methods[0]['value'], true) : [],
                'order_states'                  => (!empty($order_states[0]['value'])) ? json_decode($order_states[0]['value'], true) : [],
                'payment_statuses'              => (!empty($payment_status[0]['value'])) ? json_decode($payment_status[0]['value'], true) : [],
            ]);
    }

    public function exportPhoneOnly(Request $request) {
        $customer_phone = $request->customer_phone ?? null;
        $from = $request->show_from;
        $to = $request->show_to;
        $service_type = $request->service_type;
        $order_state = $request->order_state;
        $payment_method = $request->payment_method;
        $payment_status = $request->payment_status;
        $zone = $request->zone;
        $branch_code = $request->branch_code;
        $ftel_branch = $request->ftel_branch;
        $isAndServiceType = $request->isAndServiceType;

        if(!empty($isAndServiceType)) {
            $reportData = Sale_Report_Data_Multi_Service::selectRaw('customer_phone, customer_name, group_concat(service_type) AS combine_service');
        }
        else {
            $reportData = Sale_Report_Data_Multi_Service::selectRaw('distinct customer_phone')->leftJoin('employees', 'sale_report_data_multi_service.referral_code', '=', 'employees.phone');
        }

        if(!empty($customer_phone)) {
            $reportData->where('customer_phone', $customer_phone);
        }

        if(!empty($service_type)) {
            if(!empty($isAndServiceType)) {
                $reportData->groupBy('customer_phone');
                if(empty($service_type)) {
                    $services = Settings::where('name', 'multi_service_service_settings')->get();
                    $service_type = (!empty($services[0]['value'])) ? array_column(json_decode($services[0]['value'], true), 'key') : [];
                    // dd($result);
                }
                foreach($service_type as $keyService => $valueService) {
                    $reportData->havingRaw('Find_In_Set("' . $valueService . '", combine_service) > 0');
                }
            }
            else {
                $reportData->whereIn('service_type', $service_type);
            }
        }

        if(!empty($order_state)) {
            $reportData->whereIn('order_state', $order_state);
        }

        if(!empty($payment_method)) {
            $reportData->whereIn('payment_method', $payment_method);
        }

        if(!empty($payment_status)) {
            $reportData->whereIn('payment_status', $payment_status);
        }

        if(!empty($zone)) {
            $reportData->whereIn('employees.organization_zone_name', $zone);
        }

        if(!empty($branch_code)) {
            $reportData->whereIn('employees.organization_branch_code', $branch_code);
        }

        if(!empty($ftel_branch)) {
            $reportData->whereIn('employees.branch_name', $ftel_branch);
        }

        if(!empty($from)) {
            $from = date('Y-m-d 00:00:00', strtotime($from));
        }
        else {
            $from = date('Y-m-d 00:00:00', strtotime('yesterday midnight'));
        }

        if(!empty($to)) {
            $to = date('Y-m-d 00:00:00', strtotime($to));
        }
        else {
            $to = date('Y-m-d 23:59:59', strtotime('yesterday midnight'));
        }

        $reportData->whereBetween('t_deliver', [$from, $to]);

        if(!empty($isAndServiceType)) {
            // $result = DB::query()->fromSub($reportData, 'a')->selectRaw('distinct customer_phone')->get()->toArray();
            $result = DB::query()->fromSub($reportData, 'a')->selectRaw('distinct customer_phone')->get()->toArray();
        }
        else {
            $result = $reportData->get()->toArray();
        }

        return Excel::download(new ExportArray(['Số điện thoại khách hàng'], $result), 'ReportMultiService_' . date('YmdHis') . '.xlsx');
    }

    public function exportAll(Request $request) {
        $customer_phone = $request->customer_phone ?? null;
        $from = $request->show_from;
        $to = $request->show_to;
        $service_type = $request->service_type;
        $order_state = $request->order_state;
        $payment_method = $request->payment_method;
        $payment_status = $request->payment_status;
        $zone = $request->zone;
        $branch_code = $request->branch_code;
        $ftel_branch = $request->ftel_branch;

        $reportData = Sale_Report_Data_Multi_Service::select(['service_type_name', 'order_id', 'product_name', 'customer_phone', 'customer_name', 'address', 'total_amount_finish', 'referral_code', 'name', 'full_name', 'order_state', 'payment_method', 'payment_status', 't_create', 't_deliver', 'employees.organization_zone_name', 'employees.organization_branch_code', 'employees.branch_name'])->leftJoin('employees', 'sale_report_data_multi_service.referral_code', '=', 'employees.phone');
        if(!empty($from)) {
            $from = date('Y-m-d 00:00:00', strtotime($from));
        }
        else {
            $from = date('Y-m-d 00:00:00', strtotime('yesterday midnight'));
        }

        if(!empty($to)) {
            $to = date('Y-m-d 00:00:00', strtotime($to));
        }
        else {
            $to = date('Y-m-d 23:59:59', strtotime('yesterday midnight'));
        }

        $reportData->whereBetween('t_deliver', [$from, $to]);

        if(!empty($customer_phone)) {
            $reportData->where('customer_phone', $customer_phone);
        }

        if(!empty($service_type)) {
            $reportData->whereIn('service_type', $service_type);
        }

        if(!empty($order_state)) {
            $reportData->whereIn('order_state', $order_state);
        }

        if(!empty($payment_method)) {
            $reportData->whereIn('payment_method', $payment_method);
        }

        if(!empty($payment_status)) {
            $reportData->whereIn('payment_status', $payment_status);
        }

        if(!empty($zone)) {
            $reportData->whereIn('employees.organization_zone_name', $zone);
        }

        if(!empty($branch_code)) {
            $reportData->whereIn('employees.organization_branch_code', $branch_code);
        }

        if(!empty($ftel_branch)) {
            $reportData->whereIn('employees.branch_name', $ftel_branch);
        }

        $result = $reportData->get()->toArray();
        return Excel::download(new ExportArray(['Type', 'Order id', 'Product name', 'Customer phone', 'Customer name', 'Address', 'Total amount finish', 'Referral code', 'IBB Account', 'Referral name', 'Order state', 'Payment method', 'Payment status', 'Order create time', 'Order deliver time', 'Zone', 'Branch code', 'Branch name'], $result), 'ReportMultiService_' . date('YmdHis') . '.xlsx');
    }
}
