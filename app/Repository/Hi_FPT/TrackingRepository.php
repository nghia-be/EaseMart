<?php

namespace App\Repository\Hi_FPT;

use App\Contract\Hi_FPT\TrackingInterface;
use App\Models\Customers;
use App\Models\SectionLog;
use App\Repository\RepositoryAbstract;
use App\Services\TrackingService;
use Illuminate\Support\Facades\DB;

class TrackingRepository extends RepositoryAbstract implements TrackingInterface
{
    protected $model;
    public function __construct(SectionLog $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    public function userAnalytics($dataTable, $request)
    {

        $date = split_date($request->daterange);
        if (!empty($date[0])) {
            $from = $date[0];
            $to = $date[1];
        }
        $customer_id = $request->cusId;
        $customer = Customers::where('phone', $customer_id)->first('customer_id');
        if (!empty($customer)) {
            $customer_id = $customer->customer_id;
        }
        $limit = (int) $request->length ?? 10;
        $currentPage = $request->start == 0 ? 0 : ($request->start / $limit);
        //$offset = (int) ($currentPage-1)*$limit;
        if (!empty($customer_id) && $date) {
            $service = new TrackingService();
            $data = $service->get_detail_customers((int)$customer_id, $from??'2023-02-17 00:00:00',$to??'2024-02-17 23:59:59', $limit??10, $currentPage??0);
        }
        return $dataTable->with([
            'data' => $data->detail ?? []
        ])->render('tracking.user');
    }

    public function views($dataTable, $request)
    {
        $service = new TrackingService();

        $date = split_date($request->daterange);
        if (!empty($date[0])) {
            $from = $date[0];
            $to = $date[1];
        }

        $service->get_detail_customers($request->customer_id, $from ?? null, $to ?? null);
        return $dataTable->with([
            'data' => json_decode(123)
        ])->render('tracking.user');
    }

    public function sessionAnalytics($dataTable, $request)
    {
        $total = $this->model->count();
        $new = $this->model->where('created_at', today())->count();
        $unique = $this->model->groupBy('phone')->count();

        return view('tracking.session', [
            'detail'    => $dataTable->html(),
            'data'      => ['total_section' => $total, 'new_section'=>$new, 'unique_section'=>$unique]
        ]);
    }

    public function journeyAnalysis()
    {
        return view('tracking.journey-analysis');
    }

}
