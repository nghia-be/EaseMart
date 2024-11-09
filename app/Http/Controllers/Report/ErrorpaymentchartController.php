<?php

namespace App\Http\Controllers\Report;

use App\DataTables\Hi_FPT\SaleReportByDateDataTable;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MY_Controller;
use App\Http\Traits\DataTrait;
use App\Services\NewsEventService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\Settings;
use App\Models\Payment_Products;
use App\Models\Payment_Error_Code;
use App\Models\Payment_Orders;

use DateTime;

class ErrorpaymentchartController extends BaseController
{
    //
    use DataTrait;
    // protected $module_name = 'SupportSystem';
    // protected $model_name = "SupportSystem";
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Báo cáo thanh toán thất bại';
        // $this->model = $this->getModel('SupportSystem');
    }
    public function index(Request $request, SaleReportByDateDataTable $dataTable) {
        return view('report.errorpaymentchart');
    }

    public function getPaymentErrorUserSystem(Request $request) {
        $from_before = $request->from_last;
        $to_before = $request->to_last;
        $from = $request->from;
        $to = $request->to;
        $type = $request->type;

        $user_error_color = rand_color();
        $system_error_color = rand_color();
        $result = [];
        if(empty($from)) {
            $from = date('Y-m-01 00:00:00', strtotime('yesterday midnight'));
        }
        else {
            $from = date('Y-m-d 00:00:00', strtotime($from));
        }
        if(empty($to)) {
            $to = date('Y-m-d 23:59:59', strtotime('today midnight'));
        }
        else {
            $to = date('Y-m-d 23:59:59', strtotime($to));
        }

        $fromDate = new DateTime($from);
        $toDate = new DateTime($to);

        if($type == 'ftel' && empty($from_before)) {
            $from_before = date('Y-m-01 00:00:00', strtotime('-1 month', strtotime($from)));
            $to_before = date('Y-m-d 23:59:59', strtotime('-1 month', strtotime($to)));
        }
        elseif($type == 'ecom' && empty($from_before)) {
            $difference = $toDate->diff($fromDate);
            $difference_number = $difference->d + 1;
            $from_before = date('Y-m-d 00:00:00', strtotime('-' . $difference_number . 'days', strtotime($from)));
            $to_before = date('Y-m-d 23:59:59', strtotime('-' . $difference_number . 'days', strtotime($to)));
        }
        else {
            $from_before = date('Y-m-d 00:00:00', strtotime($from_before));
            $to_before = date('Y-m-d 23:59:59', strtotime($to_before));
        }

        $data_now = Payment_Orders::selectRaw('err_code.is_system AS error_type, COUNT(payment_provider_status) AS count')
                                  ->join('payment_error_code AS err_code', DB::raw('BINARY view_payment_orders.payment_provider_status'), '=', DB::raw('BINARY err_code.code_error'))
                                  ->join('payment_product', DB::raw('BINARY view_payment_orders.payment_type'), '=', DB::raw('BINARY payment_product.code'))
                                  ->where('payment_type', '!=', 'TOKEN')
                                  ->where('payment_provider_status', '!=', 'SUCCESS')
                                  ->where('payment_product.type', $type)
                                  ->whereBetween('date_created', [$from, $to])
                                  ->groupBy('err_code.is_system')
                                  ->orderBy('err_code.is_system')
                                  ->get()->toArray();

        $data_last = Payment_Orders::selectRaw('err_code.is_system AS error_type, COUNT(payment_provider_status) AS count')
                                  ->join('payment_product', DB::raw('BINARY view_payment_orders.payment_type'), '=', DB::raw('BINARY payment_product.code'))
                                  ->join('payment_error_code AS err_code', DB::raw('BINARY view_payment_orders.payment_provider_status'), '=', DB::raw('BINARY err_code.code_error'))
                                  ->where('payment_type', '!=', 'TOKEN')
                                  ->where('payment_provider_status', '!=', 'SUCCESS')
                                  ->where('payment_product.type', $type)
                                  ->whereBetween('date_created', [$from_before, $to_before])
                                  ->groupBy('err_code.is_system')
                                  ->orderBy('err_code.is_system')
                                  ->get()->toArray();

        $result = [
            [
                'label'             => 'Lỗi người dùng',
                'data'              => [(isset($data_last[0]['count'])) ? $data_last[0]['count'] : 0, (isset($data_now[0]['count'])) ? $data_now[0]['count']  : 0],
                'borderColor'       => $user_error_color,
                'backgroundColor'   => $user_error_color,
            ],
            [
                'label'             => 'Lỗi hệ thống',
                'data'              => [(isset($data_last[1]['count'])) ? $data_last[1]['count'] : 0, (isset($data_now[1]['count'])) ? $data_now[1]['count'] : 0],
                'borderColor'       => $system_error_color,
                'backgroundColor'   => $system_error_color,
            ]
        ];

        return ['labels' => [date('d/m/Y', strtotime($from_before)) . ' - ' . date('d/m/Y', strtotime($to_before)), date('d/m/Y', strtotime($from)) . ' - ' . date('d/m/Y', strtotime($to))], 'datasets' => $result];
    }

    public function getPaymentErrorDetail(Request $request) {
        $result = [
            'labels'                => [],
            'datasets'              => [[
                'data'              => [],
                'backgroundColor'   => []
            ]]
        ];
        $from = $request->from;
        $to = $request->to;
        $type = $request->type;
        $is_system = $request->is_system;

        if(empty($from)) {
            $from = date('Y-m-01 00:00:00', strtotime('yesterday midnight'));
        }
        else {
            $from = date('Y-m-d 00:00:00', strtotime($from));
        }
        if(empty($to)) {
            $to = date('Y-m-d 23:59:59', strtotime('today midnight'));
        }
        else {
            $to = date('Y-m-d 23:59:59', strtotime($to));
        }

        $datana = Payment_Orders::selectRaw('"#N/A" AS "description_error", COUNT(payment_provider_status) AS count, err_code.color AS color')
                               ->leftJoin('payment_error_code AS err_code', DB::raw('BINARY view_payment_orders.payment_provider_status'), '=', DB::raw('BINARY err_code.code_error'))
                               ->join('payment_product', DB::raw('BINARY view_payment_orders.payment_type'), '=', DB::raw('BINARY payment_product.code'))
                               ->where('payment_type', '!=', 'TOKEN')
                               ->where('payment_provider_status', '!=', 'SUCCESS')
                               ->where('payment_product.type', $type)
                               ->whereNull('err_code.code_error')
                               ->when(!empty($is_system), function ($query, $is_system) {
                                    $query->where('err_code.is_system', $is_system);
                               })
                               ->whereBetween('date_created', [$from, $to]);

        $data = Payment_Orders::selectRaw('err_code.description_error AS description_error, payment_provider_status AS payment_provider_status, COUNT(payment_provider_status) AS count, err_code.color AS color')
                              ->leftJoin('payment_error_code AS err_code', DB::raw('BINARY view_payment_orders.payment_provider_status'), '=', DB::raw('BINARY err_code.code_error'))
                              ->leftJoin('payment_product', DB::raw('BINARY view_payment_orders.payment_type'), '=', DB::raw('BINARY payment_product.code'))
                              ->where('payment_type', '!=', 'TOKEN')
                              ->where('payment_provider_status', '!=', 'SUCCESS')
                              ->where('payment_product.type', $type)
                              ->when(!empty($is_system), function ($query, $is_system) {
                                    $query->where('err_code.is_system', $is_system);
                              })
                              ->whereBetween('date_created', [$from, $to])
                              ->groupBy('payment_provider_status')
                            //   ->union($datana)
                              ->orderBy('count', 'desc')
                              ->get()->toArray();
        // dd($data);
        foreach($data as $key => $value) {
            $result['labels'][] = (!empty($value['description_error'])) ? $value['description_error'] : $value['payment_provider_status'];
            $result['datasets'][0]['data'][] = $value['count'];
            $result['datasets'][0]['backgroundColor'][] = (!empty($value['color'])) ? '#' . $value['color'] : '#ad8806';
        }

        return $result;
    }
}
