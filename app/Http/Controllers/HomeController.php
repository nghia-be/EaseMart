<?php

namespace App\Http\Controllers;

use App\Models\Payment_Orders;
use App\Services\ChartService;

use function PHPSTORM_META\map;
use Yajra\DataTables\DataTables;

use App\Models\Payment_Error_Code;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class HomeController extends BaseController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     * 
     */
    public function __construct()
    {
        parent::__construct();
        $this->title = 'EaseMart';
    }
    public function index()
    {
        return view('homev2');
    }
    public function getDataChart()
    {

        $charService = new ChartService();
        $result = [];
        $keyName = config('constants.REDIS_KEY.CHART_DOANH_THU_BAO_HIEM_HDI');
        if (Redis::exists($keyName)) {
            $result = unserialize(Redis::get($keyName));
            $prev_date = date('d-m-Y', strtotime("-1 days"));
            if (!array_search($prev_date, array_column($result, 'date'))) {
                $data_prev_day = $charService->getDataChartADayAgo();
                if (empty($data_prev_day)) {
                    $data_prev_day[0] = [
                        'REVENUE' => 0,
                        'REVENUE_XEMAY' => 0,
                        'REVENUE_XEOTO' => 0,
                        'TOTAL' => 0,
                        'XEMAY' => 0,
                        'XEOTO' => 0,
                        'date' => $prev_date
                    ];
                }
                $result[$prev_date] = $data_prev_day[0];
                $ttl = Redis::ttl($keyName);
                Redis::setex($keyName,($ttl <= 1) ? 86400 : $ttl, serialize($result));
            }

        } else {
            $data = $charService->getDataChart30DaysAgo();
            foreach($data as $doanhthu){
                $result[$doanhthu->date] = $doanhthu;
            }
            Redis::setex($keyName,86400, serialize($result));
        }
        return $result;
    }

    public function getPaymentErrorTableData() {
        $from = date('Y-m-01 00:00:00', strtotime('yesterday midnight'));
        $to = date('Y-m-d 23:59:59', strtotime('today midnight'));

        $data = Payment_Orders::selectRaw('payment_provider_status AS error_code, err_code.description_error AS error_name, COUNT(payment_provider_status) AS count')
                              ->join('payment_error_code AS err_code', DB::raw('BINARY view_payment_orders.payment_provider_status'), '=', DB::raw('BINARY err_code.code_error'))
                              ->where('payment_provider_status', '!=', 'SUCCESS')
                              ->where('payment_type', '!=', 'TOKEN')
                              ->whereBetween('date_created', [$from, $to])
                              ->groupBy('payment_provider_status')
                              ->get()->toArray();
        $dataNa = Payment_Orders::selectRaw('COUNT(payment_provider_status) AS count')
                                ->leftJoin('payment_error_code AS err_code', DB::raw('BINARY view_payment_orders.payment_provider_status'), '=', DB::raw('BINARY err_code.code_error'))
                                ->where('payment_provider_status', '!=', 'SUCCESS')
                                ->where('payment_type', '!=', 'TOKEN')
                                ->whereNull('err_code.code_error')
                                ->whereBetween('date_created', [$from, $to])
                                ->get()->toArray();
        array_push($data, ['error_code' => '#N/A', 'error_name' => '#N/A', 'count' => (isset($dataNa[0]['count'])) ? $dataNa[0]['count'] : 0]);
        $totals = array_column($data, 'count');
        array_push($data, ['error_code' => 'total', 'error_name' => 'Total', 'count' => array_sum($totals)]);
        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }
}
