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

use App\Models\Settings;
use App\Models\Hdi_Orders;
use App\Models\Laptop_Orders;
use App\Models\Employees;
use App\Models\Sale_Report_By_Range_Doanh_Thu;
use App\Models\Sale_Report_By_Range_Product_Doanh_Thu;
use App\Models\Sale_Report_By_Range_Product_Category_Doanh_Thu;
use App\Models\Vietlott_Orders;

use DateTime;
use DatePeriod;
use DateInterval;

class SalereportbydatedoanhthuController extends BaseController
{
    //
    use DataTrait;
    // protected $module_name = 'SupportSystem';
    // protected $model_name = "SupportSystem";
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Báo cáo kinh doanh';
        // $this->model = $this->getModel('SupportSystem');
    }
    public function index(Request $request) {
        // dd($request->all());
        $services = (['ict', 'hdi', 'household', 'vuanem', 'gas', 'vietlott']);
        // $services = (['vuanem']);
        $services_filter = (!empty($request->services)) ? $request->services : $services;
        $from1 = $request->show_from1;
        $to1 = $request->show_to1;
        $from2 = $request->show_from;
        $to2 = $request->show_to;
        $zone = $request->zone;

        $zones = Settings::where('name', 'active_zones')->get()->toArray();
        $zones_filter = json_decode($zones[0]['value'], true);

        if(empty($from2)) {
            $from2 = date('Y-m-01 00:00:00', strtotime('yesterday midnight'));
        }
        else {
            $from2 = date('Y-m-d 00:00:00', strtotime($from2));
        }
        if(empty($to2)) {
            $to2 = date('Y-m-d 23:59:59', strtotime('yesterday midnight'));
        }
        else {
            $to2 = date('Y-m-d 23:59:59', strtotime($to2));
        }
        if(empty($zone)) {
            $zone = array_column($zones_filter, 'key');
        }

        $fromDate = new DateTime($from2);
        $toDate = new DateTime($to2);
        $difference = $toDate->diff($fromDate);
        $difference_number = $difference->d + 1;

        if(empty($from1)) {
            $from1 = date('Y-m-d 00:00:00', strtotime('-' . $difference_number . 'days', strtotime($from2)));
        }
        else {
            $from1 = date('Y-m-d 00:00:00', strtotime($from1));
        }
        if(empty($to1)) {
            $to1 = date('Y-m-d 23:59:59', strtotime('-' . $difference_number . 'days', strtotime($to2)));
        }
        else {
            $to1 = date('Y-m-d 23:59:59', strtotime($to1));
        }

        $query1 = Sale_Report_By_Range_Doanh_Thu::selectRaw("service,
                                                zone,
                                                NULL AS branch_name,
                                                SUM(IF(DATE(date_created) BETWEEN '" . $from1 . "' AND '" . $to1 . "', count, 0)) AS 'count_last_time',
                                                SUM(IF(DATE(date_created) BETWEEN '" . $from1 . "' AND '" . $to1 . "', amount, 0)) AS 'amount_last_time',
                                                SUM(IF(DATE(date_created) BETWEEN '" . $from2 . "' AND '" . $to2 . "', count, 0)) AS 'count_this_time',
                                                SUM(IF(DATE(date_created) BETWEEN '" . $from2 . "' AND '" . $to2 . "', amount, 0)) AS 'amount_this_time',
                                                GROUP_CONCAT(IF(DATE(date_created) BETWEEN '" . $from1 . "' AND '" . $to1 . "', list_emp_phone, null)) AS 'count_employees_last_time',
                                                GROUP_CONCAT(IF(DATE(date_created) BETWEEN '" . $from2 . "' AND '" . $to2 . "', list_emp_phone, null)) AS 'count_employees_this_time'")
                                        ->whereIn('zone', array_diff($zone, ['FTELHO', 'PNCHO', 'TINHO', 'App Users']))
                                        ->whereIn('service', $services_filter)
                                        ->whereBetween('date_created', [$from1, $to2])
                                        ->groupBy(['service', 'zone']);

        $data = Sale_Report_By_Range_Doanh_Thu::selectRaw("service,
                                                zone,
                                                branch_name,
                                                SUM(IF(DATE(date_created) BETWEEN '" . $from1 . "' AND '" . $to1 . "', count, 0)) AS 'count_last_time',
                                                SUM(IF(DATE(date_created) BETWEEN '" . $from1 . "' AND '" . $to1 . "', amount, 0)) AS 'amount_last_time',
                                                SUM(IF(DATE(date_created) BETWEEN '" . $from2 . "' AND '" . $to2 . "', count, 0)) AS 'count_this_time',
                                                SUM(IF(DATE(date_created) BETWEEN '" . $from2 . "' AND '" . $to2 . "', amount, 0)) AS 'amount_this_time',
                                                GROUP_CONCAT(IF(DATE(date_created) BETWEEN '" . $from1 . "' AND '" . $to1 . "', list_emp_phone, null)) AS 'count_employees_last_time',
                                                GROUP_CONCAT(IF(DATE(date_created) BETWEEN '" . $from2 . "' AND '" . $to2 . "', list_emp_phone, null)) AS 'count_employees_this_time'")
                                    ->whereIn('service', $services_filter)
                                    ->whereIn('zone', $zone)
                                    ->whereBetween('date_created', [$from1, $to2])
                                    ->groupBy(['service', 'zone', 'branch_name'])
                                    ->union($query1)
                                    ->orderBy('service')
                                    ->orderBy('zone')
                                    ->orderBy('branch_name')
                                    ->get()
                                    ->groupBy(['service'])
                                    ->toArray();

        $total = Sale_Report_By_Range_Doanh_Thu::selectRaw("service,
                                                'Total' AS zone,
                                                NULL AS branch_name,
                                                SUM(IF(DATE(date_created) BETWEEN '" . $from1 . "' AND '" . $to1 . "', count, 0)) AS 'count_last_time',
                                                SUM(IF(DATE(date_created) BETWEEN '" . $from1 . "' AND '" . $to1 . "', amount, 0)) AS 'amount_last_time',
                                                SUM(IF(DATE(date_created) BETWEEN '" . $from2 . "' AND '" . $to2 . "', count, 0)) AS 'count_this_time',
                                                SUM(IF(DATE(date_created) BETWEEN '" . $from2 . "' AND '" . $to2 . "', amount, 0)) AS 'amount_this_time',
                                                GROUP_CONCAT(IF(DATE(date_created) BETWEEN '" . $from1 . "' AND '" . $to1 . "', list_emp_phone, null)) AS 'count_employees_last_time',
                                                GROUP_CONCAT(IF(DATE(date_created) BETWEEN '" . $from2 . "' AND '" . $to2 . "', list_emp_phone, null)) AS 'count_employees_this_time'")
                                        ->whereIn('service', $services_filter)
                                        ->whereIn('zone', $zone)
                                        ->whereBetween('date_created', [$from1, $to2])
                                        ->groupBy(['service'])
                                        ->get()
                                        ->groupBy(['service'])
                                        ->toArray();

        $total_service = [
            'count'     => 0,
            'amount'    => 0
        ];
        foreach($data as $key => &$value) {
            $total_service['count'] += intval($total[$key][0]['count_this_time']);
            $total_service['amount'] += intval($total[$key][0]['amount_this_time']);
            array_push($value, $total[$key][0]);
        }

        // count by product type
        $data_product = Sale_Report_By_Range_Doanh_Thu::selectRaw("service,
                                                    SUM(count) AS 'count_this_time',
                                                    SUM(amount) AS 'amount_this_time'")
                                            ->whereIn('service', $services_filter)
                                            ->whereIn('zone', $zone)
                                            ->whereBetween('date_created', [$from2, $to2])
                                            ->groupBy(['service'])
                                            ->get()
                                            ->groupBy(['service'])
                                            ->toArray();


        if(in_array('vietlott', $services_filter)) {
            $data_vietlott_total = Vietlott_Orders::selectRaw("'Total' AS product_name,
                        SUM(IF(DATE(t_create) BETWEEN '" . $from1 . "' AND '" . $to1 . "', quantity, 0)) AS 'count_last_time',
                        SUM(IF(DATE(t_create) BETWEEN '" . $from1 . "' AND '" . $to1 . "', product_price * quantity - discount_price, 0)) AS 'amount_last_time',
                        SUM(IF(DATE(t_create) BETWEEN '" . $from2 . "' AND '" . $to2 . "', quantity, 0)) AS 'count_this_time',
                        SUM(IF(DATE(t_create) BETWEEN '" . $from2 . "' AND '" . $to2 . "', product_price * quantity - discount_price, 0)) AS 'amount_this_time'")
                ->where('order_status', 'SUCCESS')
                ->whereBetween('t_create', [$from1, $to2]);
            $data_vietlott = Vietlott_Orders::selectRaw("product_name,
                        SUM(IF(DATE(t_create) BETWEEN '" . $from1 . "' AND '" . $to1 . "', quantity, 0)) AS 'count_last_time',
                        SUM(IF(DATE(t_create) BETWEEN '" . $from1 . "' AND '" . $to1 . "', product_price * quantity - discount_price, 0)) AS 'amount_last_time',
                        SUM(IF(DATE(t_create) BETWEEN '" . $from2 . "' AND '" . $to2 . "', quantity, 0)) AS 'count_this_time',
                        SUM(IF(DATE(t_create) BETWEEN '" . $from2 . "' AND '" . $to2 . "', product_price * quantity - discount_price, 0)) AS 'amount_this_time'")
            ->where('order_status', 'SUCCESS')
            ->whereBetween('t_create', [$from1, $to2])
            ->groupBy(['product_name'])
            ->union($data_vietlott_total)
            ->get()
            ->toArray();
        }
        else {
            $data_vietlott = [];
        }

        $productByService = Sale_Report_By_Range_Product_Doanh_Thu::selectRaw("product_type,
                                                                                SUM(IF(DATE(created_at) BETWEEN '" . $from1 . "' AND '" . $to1 . "', count, 0)) AS 'count_last_time',
                                                                                SUM(IF(DATE(created_at) BETWEEN '" . $from1 . "' AND '" . $to1 . "', amount, 0)) AS 'amount_last_time',
                                                                                SUM(IF(DATE(created_at) BETWEEN '" . $from2 . "' AND '" . $to2 . "', count, 0)) AS 'count_this_time',
                                                                                SUM(IF(DATE(created_at) BETWEEN '" . $from2 . "' AND '" . $to2 . "', amount, 0)) AS 'amount_this_time',
                                                                                service")
                                                        ->whereIn('service', $services_filter)
                                                        ->whereIn('zone', $zone)
                                                        ->whereBetween('created_at', [$from1, $to2])
                                                        ->groupBy(['service', 'product_type'])
                                                        ->orderBy('amount', 'desc')
                                                        ->get()
                                                        ->groupBy(['service'])
                                                        ->toArray();
        // dd($productByService);
        $productByCategory = Sale_Report_By_Range_Product_Category_Doanh_Thu::selectRaw("product_category,
                                                                                        SUM(IF(DATE(created_at) BETWEEN '" . $from1 . "' AND '" . $to1 . "', count, 0)) AS 'count_last_time',
                                                                                        SUM(IF(DATE(created_at) BETWEEN '" . $from1 . "' AND '" . $to1 . "', amount, 0)) AS 'amount_last_time',
                                                                                        SUM(IF(DATE(created_at) BETWEEN '" . $from2 . "' AND '" . $to2 . "', count, 0)) AS 'count_this_time',
                                                                                        SUM(IF(DATE(created_at) BETWEEN '" . $from2 . "' AND '" . $to2 . "', amount, 0)) AS 'amount_this_time',
                                                                                        service")
                                                        ->whereIn('service', $services_filter)
                                                        ->whereIn('zone', $zone)
                                                        ->whereBetween('created_at', [$from1, $to2])
                                                        ->groupBy(['service', 'product_category'])
                                                        ->orderBy('amount', 'desc')
                                                        ->get()
                                                        ->groupBy(['service'])
                                                        ->toArray();


        $chartStartFrom = date('Y-m-d 00:00:00', strtotime('yesterday midnight -30 days'));
        $chartStartTo = date('Y-m-d 23:59:59', strtotime('yesterday midnight'));
        $productByDateRaw = Sale_Report_By_Range_Doanh_Thu::selectRaw('SUM(count) AS count, SUM(amount) AS amount, DATE_FORMAT(date_created, "%Y-%m-%d") AS date_created')
                                                                ->whereBetween('date_created', [$from2, $to2])
                                                                ->whereIn('service', $services_filter)
                                                                ->whereIn('zone', $zone)
                                                                ->orderBy('date_created')
                                                                ->groupBy(['date_created'])
                                                                ->get()
                                                                ->toArray();
        // dd($productByDateRaw);
        $productByDateRaw = array_column($productByDateRaw, null, 'date_created');
        // dd($productByDateRaw);

        if(in_array('vietlott', $services_filter)) {
            $vietlottByDateRaw = Vietlott_Orders::selectRaw('COUNT(trans_id) AS count, SUM(product_price * quantity - discount_price) AS amount, DATE_FORMAT(t_create, "%Y-%m-%d") AS created_at')
                        ->where('order_status', 'SUCCESS')
                        ->whereBetween('t_create', [$from2, $to2])
                        ->orderBy('created_at')
                        ->groupBy(['created_at'])
                        ->get()
                        ->toArray();

            $vietlottByDateRaw = array_column($vietlottByDateRaw, null, 'created_at');
        }

        // dd($vietlottByDateRaw);
        $productByDateChart = [
            [
                'label'             => 'Số tiền',
                'data'              => [],
                'backgroundColor'   => 'rgba(54, 162, 235, 0.5)',
                'borderColor'       => 'rgba(54, 162, 235, 1)',
                'yAxisID'           => 'money',
                'order'             => 2,
                'datalabels'        => [
                    'color'         => 'black',
                    'anchor'        => 'end',
                    'align'         => 'top',
                    'offset'        => 5,
                ]
            ],
            [
                'type'              => 'line',
                'label'             => 'Số lượng đơn hàng',
                'data'              => [],
                'borderColor'       => 'rgba(0, 0, 0, 0.5)',
                'yAxisID'           => 'quantity',
                'order'             => 1,
                'pointBorderWidth'  => 3,
                'pointStyle'        => 'circle',
                'datalabels'        => [
                    'color'         => 'red',
                    'anchor'        => 'end',
                    'align'         => 'top',
                    'offset'        => 5,
                ]
            ]
        ];

        $productByDateChartLabel = [];
        $thisMonthYear = date('Y-m', strtotime('yesterday midnight'));
        $interval = new DateInterval('P1D');
        $realEnd = new DateTime($to2);
        // $realEnd->add($interval);
        $period = new DatePeriod(new DateTime($from2), $interval, $realEnd);

        // if()

        foreach ($period as $periodKey => $periodValue) {
            $productByDateChartLabel[] = strval($periodValue->format('Y-m-d'));
            $productByDateChart[0]['data'][] = strval((!empty($productByDateRaw[strval($periodValue->format('Y-m-d'))]['amount']) ? $productByDateRaw[strval($periodValue->format('Y-m-d'))]['amount'] : 0) + (!empty($vietlottByDateRaw[strval($periodValue->format('Y-m-d'))]['amount']) ? $vietlottByDateRaw[strval($periodValue->format('Y-m-d'))]['amount'] : 0));
            $productByDateChart[1]['data'][] = strval((!empty($productByDateRaw[strval($periodValue->format('Y-m-d'))]['count']) ? $productByDateRaw[strval($periodValue->format('Y-m-d'))]['count'] : 0) + (!empty($vietlottByDateRaw[strval($periodValue->format('Y-m-d'))]['count']) ? $vietlottByDateRaw[strval($periodValue->format('Y-m-d'))]['count'] : 0));
        }

        // dd($productByDateChart);

        $productByProductTypeChartLabel = [];
        $productByProductTypeChart = [
            [
                'label'             => 'Số tiền',
                'data'              => [],
                'backgroundColor'   => 'rgba(78, 181, 18, 0.5)',
                'borderColor'       => 'rgba(78, 181, 18, 1)',
                'yAxisID'           => 'money',
                'order'             => 2,
                'datalabels'        => [
                    'color'         => 'black',
                    'anchor'        => 'end',
                    'align'         => 'top',
                    'offset'        => 5
                ]
            ],
            [
                'type'              => 'line',
                'label'             => 'Số lượng đơn hàng',
                'data'              => [],
                'borderColor'       => 'rgba(0, 0, 0, 0.5)',
                'yAxisID'           => 'quantity',
                'order'             => 1,
                'pointBorderWidth'  => 3,
                'pointStyle'        => 'circle',
                'datalabels'        => [
                    'color'         => 'red',
                    'anchor'        => 'end',
                    'align'         => 'top',
                    'offset'        => 5,
                ]
            ]
        ];

        $totalThisMonth = Sale_Report_By_Range_Doanh_Thu::selectRaw("service,
                                                'Total' AS zone,
                                                NULL AS branch_name,
                                                SUM(count) AS 'count_this_time',
                                                SUM(amount) AS 'amount_this_time'")
                                        ->whereBetween('date_created', [$from2, $to2])
                                        ->whereIn('service', $services_filter)
                                        ->whereIn('zone', $zone)
                                        ->groupBy(['service'])
                                        ->get()
                                        ->groupBy(['service'])
                                        ->toArray();

        foreach($totalThisMonth as $totalThisMonthKey => $totalThisMonthValue) {
            $productByProductTypeChartLabel[] = strtoupper($totalThisMonthKey);
            $productByProductTypeChart[0]['data'][] = $totalThisMonthValue[0]['amount_this_time'];
            $productByProductTypeChart[1]['data'][] = $totalThisMonthValue[0]['count_this_time'];
        }

        if(in_array('vietlott', $services_filter)) {
            $data_vietlott_total_this_month = Vietlott_Orders::selectRaw("'Total' AS product_name,
                                                                        SUM(quantity) AS 'count_this_time',
                                                                        SUM(product_price * quantity - discount_price) AS 'amount_this_time'")
                                                                ->where('order_status', 'SUCCESS')
                                                                ->whereBetween('t_create', [$from2, $to2])
                                                                ->get()
                                                                ->toArray();
            $productByProductTypeChartLabel[] = 'VIETLOTT';
            $productByProductTypeChart[0]['data'][] = (!empty($data_vietlott_total_this_month[0]['amount_this_time'])) ? $data_vietlott_total_this_month[0]['amount_this_time'] : '0';
            $productByProductTypeChart[1]['data'][] = (!empty($data_vietlott_total_this_month[0]['count_this_time'])) ? $data_vietlott_total_this_month[0]['count_this_time'] : '0';
        }

        // dd($productByProductTypeChart);

        $serviceColor = ['ict' => 'rgba(138, 96, 232, 0.5)', 'hdi' => 'rgba(62, 224, 205, 0.5)', 'household' => 'rgba(31, 101, 89, 0.5)', 'vuanem' => 'rgba(158, 190, 27, 0.5)', 'gas' => 'rgba(210, 94, 32, 0.5)', 'vietlott' => 'rgba(253, 45, 131, 0.5)'];
        $productByBranchChartRaw = Sale_Report_By_Range_Doanh_Thu::selectRaw("zone, service, SUM(count) AS 'count_this_time', SUM(amount) AS 'amount_this_time'")
                                                                ->whereBetween('date_created', [$from2, $to2])
                                                                ->whereIn('service', $services_filter)
                                                                ->whereIn('zone', $zone)
                                                                ->orderBy('zone')
                                                                ->orderBy('service')
                                                                ->groupBy(['zone', 'service'])
                                                                ->get()
                                                                ->toArray();

        $productByBranchChartLabel = [];
        $productByBranchChart = [
            'line'                  => [
                'type'              => 'line',
                'label'             => 'Số lượng đơn hàng',
                'data'              => [],
                'borderColor'       => 'rgba(0, 0, 0, 0.5)',
                'yAxisID'           => 'quantity',
                'order'             => 1,
                'pointBorderWidth'  => 3,
                'pointStyle'        => 'circle',
                'datalabels'        => [
                    'color'         => 'red',
                    'offset'        => 5,
                ]
            ]
        ];

        foreach(collect($productByBranchChartRaw)->groupBy('zone')->toArray() as $productByBranchChartKey => $productByBranchChartValue) {
            $productByBranchChartLabel[] = $productByBranchChartKey;
            $productByBranchChart['line']['data'][] = strval(array_sum(array_map(function($serviceRow) {
                return intval($serviceRow['count_this_time']) ;
            }, $productByBranchChartValue)) + (($productByBranchChartKey == 'App Users' && !empty($data_vietlott[count($data_vietlott) - 1]['count_this_time'])) ? $data_vietlott[count($data_vietlott) - 1]['count_this_time'] : 0));
            $amount_chart_data = array_column($productByBranchChartValue, 'amount_this_time', 'service');
            foreach($services_filter as $serviceFilterKey => $serviceFilterValue) {
                if(empty($productByBranchChart[$serviceFilterValue])) {
                    $productByBranchChart[$serviceFilterValue] = [
                        'type'              => 'bar',
                        'label'             => strtoupper($serviceFilterValue),
                        'data'              => [(!empty($amount_chart_data[$serviceFilterValue])) ? $amount_chart_data[$serviceFilterValue] : '0'],
                        'borderColor'       => 'rgba(0, 0, 0, 0.5)',
                        'backgroundColor'   => $serviceColor[$serviceFilterValue],
                        'yAxisID'           => 'money',
                        'order'             => 2,
                        'pointStyle'        => 'circle',
                        'datalabels'        => [
                            'color'         => 'black',
                            'offset'        => 5,
                        ]
                    ];
                }
                else {
                    $productByBranchChart[$serviceFilterValue]['data'][] = (!empty($amount_chart_data[$serviceFilterValue])) ? $amount_chart_data[$serviceFilterValue] : '0';
                }
            }
        }
        if(in_array('vietlott', $services_filter)) {
            $productByBranchChart['vietlott'] = [
                'type' => 'bar',
                'label'             => strtoupper($serviceFilterValue),
                'data'              => [strval($data_vietlott[count($data_vietlott) - 1]['amount_this_time'])],
                'borderColor'       => 'rgba(0, 0, 0, 0.5)',
                'backgroundColor'   => $serviceColor[$serviceFilterValue],
                'yAxisID'           => 'money',
                'order'             => 2,
                'pointStyle'        => 'circle',
                'datalabels'        => [
                    'color'         => 'black',
                    'offset'        => 5,
                ]
            ];
            $total_service['count'] += $data_vietlott[count($data_vietlott) - 1]['count_this_time'];
            $total_service['amount'] += $data_vietlott[count($data_vietlott) - 1]['amount_this_time'];
        }

        $data_between_to_time = Sale_Report_By_Range_Doanh_Thu::selectRaw("zone,
                    SUM(IF(DATE(date_created) BETWEEN '" . $from1 . "' AND '" . $to1 . "', count, 0)) AS 'count_last_time',
                    SUM(IF(DATE(date_created) BETWEEN '" . $from1 . "' AND '" . $to1 . "', amount, 0)) AS 'amount_last_time',
                    SUM(IF(DATE(date_created) BETWEEN '" . $from2 . "' AND '" . $to2 . "', count, 0)) AS 'count_this_time',
                    SUM(IF(DATE(date_created) BETWEEN '" . $from2 . "' AND '" . $to2 . "', amount, 0)) AS 'amount_this_time'")
            ->whereIn('zone', $zone)
            ->whereIn('service', $services_filter)
            ->whereBetween('date_created', [$from1, $to2])
            ->orderBy('zone')
            ->groupBy(['zone'])
            ->get()
            ->toArray();

        $data_between_to_time_chart_label = [];
        $data_between_to_time_chart = [
            'count_last_time'       => [
                'type'              => 'line',
                'label'             => 'Số lượng đơn hàng từ ' . date('d/m/Y', strtotime($from1)) . ' đến ' . date('d/m/Y', strtotime($to1)),
                'data'              => [],
                'borderColor'       => 'rgba(0, 0, 0, 0.5)',
                'yAxisID'           => 'quantity',
                'order'             => 1,
                'pointBorderWidth'  => 3,
                'pointStyle'        => 'circle',
                'datalabels'        => [
                    'color'         => 'rgba(0, 0, 0, 1)',
                    'anchor'        => 'end',
                    'align'         => 'top',
                    'offset'        => 5,
                ]
            ],
            'count_this_time'       => [
                'type'              => 'line',
                'label'             => 'Số lượng đơn hàng từ ' . date('d/m/Y', strtotime($from2)) . ' đến ' . date('d/m/Y', strtotime($to2)),
                'data'              => [],
                'borderColor'       => 'rgba(34, 133, 118, 0.5)',
                'yAxisID'           => 'quantity',
                'order'             => 1,
                'pointBorderWidth'  => 3,
                'pointStyle'        => 'circle',
                'datalabels'        => [
                    'color'         => 'rgba(34, 133, 118, 1)',
                    'anchor'        => 'end',
                    'align'         => 'top',
                    'offset'        => 5,
                ]
            ],
            'amount_last_time'      => [
                'type'              => 'bar',
                'label'             => 'Số tiền từ ' . date('d/m/Y', strtotime($from1)) . ' đến ' . date('d/m/Y', strtotime($to1)),
                'data'              => [],
                'borderColor'       => 'rgba(104, 132, 104, 1)',
                'backgroundColor'   => 'rgba(104, 132, 104, 0.5)',
                'yAxisID'           => 'money',
                'order'             => 2,
                'datalabels'        => [
                    'color'         => 'rgba(104, 132, 104, 1)',
                    'anchor'        => 'end',
                    'align'         => 'top',
                    'offset'        => 5,
                ]
            ],
            'amount_this_time'      => [
                'type'              => 'bar',
                'label'             => 'Số tiền từ ' . date('d/m/Y', strtotime($from2)) . ' đến ' . date('d/m/Y', strtotime($to2)),
                'data'              => [],
                'borderColor'       => 'rgba(248, 156, 47, 1)',
                'backgroundColor'   => 'rgba(248, 156, 47, 0.5)',
                'yAxisID'           => 'money',
                'order'             => 2,
                'datalabels'        => [
                    'color'         => 'rgba(248, 156, 47, 1)',
                    'anchor'        => 'end',
                    'align'         => 'top',
                    'offset'        => 5,
                ]
            ],
        ];

        foreach($data_between_to_time as &$data_between_to_time_row) {
            $data_between_to_time_chart_label[] = $data_between_to_time_row['zone'];
            if($data_between_to_time_row['zone'] == 'App Users' && in_array('vietlott', $services_filter)) {
                $data_between_to_time_row['count_last_time'] += $data_vietlott[count($data_vietlott) - 1]['count_last_time'];
                $data_between_to_time_row['count_this_time'] += $data_vietlott[count($data_vietlott) - 1]['count_this_time'];
                $data_between_to_time_row['amount_last_time'] += $data_vietlott[count($data_vietlott) - 1]['count_this_time'];
                $data_between_to_time_row['amount_this_time'] += $data_vietlott[count($data_vietlott) - 1]['amount_this_time'];
            }
            $data_between_to_time_chart['count_last_time']['data'][] = strval($data_between_to_time_row['count_last_time']);
            $data_between_to_time_chart['count_this_time']['data'][] = strval($data_between_to_time_row['count_this_time']);
            $data_between_to_time_chart['amount_last_time']['data'][] = strval($data_between_to_time_row['amount_last_time']);
            $data_between_to_time_chart['amount_this_time']['data'][] = strval($data_between_to_time_row['amount_this_time']);
        }
        // dd($productByCategory);
        return view('report.reportsalebydatedoanhthu', ['total_service' => $total_service, 'data' => $data, 'productByService' => $productByService, 'productByCategory' => $productByCategory, 'services' => $services, 'zones' => $zones_filter, 'last_time' => date('d/m/Y', strtotime($from1)) . ' - ' . date('d/m/Y', strtotime($to1)), 'this_time' => date('d/m/Y', strtotime($from2)) . ' - ' . date('d/m/Y', strtotime($to2)), 'data_product' => $data_product, 'data_vietlott' => @$data_vietlott, 'productByDateChart' => $productByDateChart, 'productByDateChartLabel' => array_unique($productByDateChartLabel), 'productByProductTypeChart' => $productByProductTypeChart, 'productByProductTypeChartLabel' => $productByProductTypeChartLabel, 'productByBranchChart' => array_values($productByBranchChart), 'productByBranchChartLabel' => array_unique($productByBranchChartLabel), 'from1' => date('Y-m-d', strtotime($from1)), 'to1' => date('Y-m-d', strtotime($to1)), 'from2' => date('Y-m-d', strtotime($from2)), 'to2' => date('Y-m-d', strtotime($to2)), 'services_filter' => $services_filter, 'zone_filter' => $zone, 'data_between_to_time_chart_label' => $data_between_to_time_chart_label, 'data_between_to_time_chart' => array_values($data_between_to_time_chart)]);
    }
}
