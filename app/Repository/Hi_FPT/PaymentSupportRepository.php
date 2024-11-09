<?php

namespace App\Repository\Hi_FPT;

use App\Contract\Hi_FPT\PaymentSupportInterface;
use App\Models\PaymentUnpaid;
use App\Repository\RepositoryAbstract;
use Illuminate\Support\Facades\DB;

class PaymentSupportRepository extends RepositoryAbstract implements PaymentSupportInterface
{
    protected $model;
    public function __construct(PaymentUnpaid $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    public function index($dataTable, $dataTableOverview, $params)
    {
        $data =         $this->model->select( 'status',DB::raw('COUNT(distinct customer_phone) as count'))->groupBy('status');
        $data_detail =  $this->model->groupBy('customer_phone', 'created_at');
        $table_detail = $dataTable->with([ 'data_detail'=> $data_detail]);
        $table_overview = $dataTableOverview->with(['data'=> $data]);

        if ($params->ajax() && request()->get('table') == 'detail') {
            return $table_detail->render('payment-support.index');
        }
        if ($params->ajax() && request()->get('table') == 'overview') {
            return $table_overview->render('payment-support.index');
        }

        return view('payment-support.index', [
            'overview'          => $table_overview->html(),
            'detail'            => $table_detail->html()
        ]);
    }

    public function update($params, $id)
    {
        $description = $params->input('description');
        $description_error = $params->input('description_error');
        $status = $params->input('status');
        $this->model
            ->where('customer_phone', DB::table('payment_unpaid')->find($id)->customer_phone)
            ->update(['status'=> $status, 'description_error'=>$description_error, 'description'=>$description]);
        return response()->json(['success' => 'Thành công', 'html' => 'Update thành công!']);
    }

    public function show($id)
    {
        return response()->json(['success' => 'Thành công', 'html' => 'Update thành công!', 'data' => $this->findById($id)]);
    }

}
