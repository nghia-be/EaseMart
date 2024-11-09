<?php

namespace App\Http\Controllers\Report;

use App\DataTables\Hi_FPT\ReportLaptopOrdersByProductDataTable;
use App\DataTables\Hi_FPT\ReportLaptopOrdersByProductNCCDataTable;
use App\DataTables\Hi_FPT\ReportLaptopOrdersByProductMerchantDataTable;
use App\Http\Controllers\BaseController;
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
use App\Models\Laptop_Orders_Agent;
use App\Models\Shopping_Product;
use Illuminate\Support\Facades\DB;

use App\Exports\ExportArray;
use Maatwebsite\Excel\Facades\Excel;

class ReportLaptopOrdersByProductController extends BaseController
{
    //
    use DataTrait;
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Báo cáo theo nhà cung cấp và ngành hàng';
        $this->request = null;
    }
    public function index(ReportLaptopOrdersByProductNCCDataTable $nccDataTable, ReportLaptopOrdersByProductDataTable $dataTable, ReportLaptopOrdersByProductMerchantDataTable $merchantDataTable, Request $request) {
        // dd($request);
        $merchants = Settings::where('name', 'sale_laptop_order_merchat_id')->get();
        $merchants_default = (!empty($merchants[0]['value'])) ? json_decode($merchants[0]['value'], true) : [];
        $merchants_default_key = array_column($merchants_default, 'key');
        // dd($merchants_default_key);

        $agents = Laptop_Orders_Agent::select(['agent_id', 'merchant_id', 'agent_name'])->whereIn('merchant_id', $merchants_default_key)->orderBy('agent_name')->get()->toArray();

        $products = Shopping_Product::select(['product_id', 'product_name', 'sku'])->whereIn('merchant_id', $merchants_default_key)->get()->toArray();
        $this->request = $request;

        return view('report.laptop_orders_product.main')->with([
            'filter'                        => [
                'customer_phone'            => @$request->customer_phone,
                'show_from'                 => @$request->show_from,
                'show_to'                   => @$request->show_to,
                'merchants_id_filter'       => !empty($request->merchant_id) ? $request->merchant_id : [],
                'agent_id_filter'           => !empty($request->agent_id) ? $request->agent_id : [],
                'product_id_filter'         => !empty($request->product_id) ? $request->product_id : []
            ],
            'merchants'                     => $merchants_default,
            'agents'                        => $agents,
            'products'                      => array_filter($products),
            'nccDataTable'                  => $nccDataTable->html(),
            'dataTable'                     => $dataTable->html(),
            'merchantDataTable'             => $merchantDataTable->html(),
        ]);
    }

    public function renderNccTable(ReportLaptopOrdersByProductNCCDataTable $nccDataTable, Request $request) {
        // dd($request->all());
        return $nccDataTable->with([
            'start'                             => $request->start,
            'length'                            => $request->length,
            'customer_phone'                    => $request->customer_phone,
            'from'                              => $request->show_from,
            'to'                                => $request->show_to,
            'merchant_id'                       => $request->merchant_id,
            'agent_id'                          => $request->agent_id,
            'product_id'                        => $request->product_id,
            'columns'                           => $request->columns,
        ])->render('view');
    }

    public function renderProductTable(ReportLaptopOrdersByProductDataTable $dataTable, Request $request) {
        // dd($request->all());
        return $dataTable->with([
            'start'                             => $request->start,
            'length'                            => $request->length,
            'customer_phone'                    => $request->customer_phone,
            'from'                              => $request->show_from,
            'to'                                => $request->show_to,
            'merchant_id'                       => $request->merchant_id,
            'agent_id'                          => $request->agent_id,
            'product_id'                        => $request->product_id,
            'columns'                           => $request->columns,
        ])->render('view');
    }

    public function renderMerchantTable(ReportLaptopOrdersByProductMerchantDataTable $merchantDataTable, Request $request) {
        // dd($request->all());
        return $merchantDataTable->with([
            'start'                             => $request->start,
            'length'                            => $request->length,
            'customer_phone'                    => $request->customer_phone,
            'from'                              => $request->show_from,
            'to'                                => $request->show_to,
            'merchant_id'                       => $request->merchant_id,
            'agent_id'                          => $request->agent_id,
            'product_id'                        => $request->product_id,
            'columns'                           => $request->columns,
        ])->render('view');
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
            $reportData = Sale_Report_Data_Multi_Service::selectRaw('customer_phone, customer_name, group_concat(service_type) AS combine_service, group_concat(order_state) AS order_state');
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
            // $result = DB::query()->fromSub($reportData, 'a')->selectRaw('distinct customer_phone')->get()->toArray();
            $result = $reportData->get()->toArray();
            dd($result);
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
