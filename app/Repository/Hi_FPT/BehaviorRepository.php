<?php

namespace App\Repository\Hi_FPT;

use App\Http\Traits\DataTrait;
use App\Rules\NumberPhoneRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Contract\Hi_FPT\BehaviorInterface;

class BehaviorRepository implements BehaviorInterface
{
    use DataTrait;

    public function index()
    {
        return view('behavior.index');
    }

    public function store($params)
    {
        $danhsachSDT = [];
        $phoneQr = [];
        $date_created = [];
        // Lấy data từ hàm data theo input excel hoặc filter day
        //
        foreach ($this->data($params) as $value) {
            if (!is_array($value)) {
                $value = (array) $value;
            }
            if (empty($value['phone']) || empty($value['date_created'])) {
                return back()->withErrors('Sai tiêu đề cột, vui lòng kiểm tra lại (phone & date_created)');
            }
            $phoneQr[] = $value['phone']; // push all phone to array -> check in app_log
            $date_created[] = $value['date_created']; //push all date_created to array -> validate date
            $danhsachSDT[$value['phone']] = $value['date_created']; // change format to ["phone"=>"date_created"]
        }

        $validator = $this->validator($phoneQr, $date_created);
        if ($validator->isNotEmpty()) {
            return back()->withErrors($validator->all());
        }

        // Retrieve errors message bag
        $dataSql = DB::table('app_log')
            ->select('phone', 'date_action')
            ->whereIn('phone', $phoneQr)
            ->get();

        $countDataSQl = $dataSql->count();
        if ($countDataSQl === 0) {
            return back()->withErrors(['Không có dữ liệu']);
        }

        $total = [
            '0_2' => [
                'name' => '0-2 ngày',
                '0_' => 0,
                '1_2' => 0,
                '3_4' => 0,
                '5_' => 0
            ],
            '3_5' => [
                'name' => '3-5 ngày',
                '0_' => 0,
                '1_2' => 0,
                '3_4' => 0,
                '5_' => 0
            ],
            '6_7' => [
                'name' => '6-7 ngày',
                '0_' => 0,
                '1_2' => 0,
                '3_4' => 0,
                '5_' => 0
            ]
        ];
        $thongke = [
            '0_2' => 0,
            '3_5' => 0,
            '6_7' => 0
        ];
        $phone = $dataSql[0]->phone;
        for ($i = 0; $i < $countDataSQl; $i++) {
            if ($dataSql[$i]->phone !== $phone) {
                $this->extracted_switch($thongke, $total);
                $thongke = [
                    '0_2' => 0,
                    '3_5' => 0,
                    '6_7' => 0
                ];
                $phone = $dataSql[$i]->phone;
            }
            $date = $dataSql[$i]->date_action;
            $day = strtotime($date) - strtotime($danhsachSDT[$phone]);

            switch (true) {
                case ($day <= 172800) :
                    $thongke['0_2']++;
                    break;
                case ($day <= 432000) :
                    $thongke['3_5']++;
                    break;
                case ($day <= 604800) :
                    $thongke['6_7']++;
                    break;
                default :
                    break;
            }
        }

        //Xử lý lượt check cuối cùng từ SQL:
        $data = $this->extracted_switch($thongke, $total);
        return back()->with(['data' => $data ?? [], 'success' => 'Thành công', 'html' => 'Thành công']);
    }

    public function data($params)
    {
        if (!empty($params->input('show_to')) && !empty($params->input('show_from'))) {
            $query = DB::connection('mysql4')->table('customers')
                ->select('phone', 'date_created');
            $from = changeFormatDateLocal($params->input('show_from'));
            $to = changeFormatDateLocal($params->input('show_to'));
            $phone = $params->input('phone_number');
            if(!empty($phone)) {
                $query->where('phone', $phone);
            }
            $query->whereBetween('date_created', [$from, $to]);
            $collection = $query->get()->toArray();
        } else {
            $collection = excel_import($params);
        }
        return $collection;
    }

    public function validator($phoneQr, $date_created)
    {
        return Validator::make([
            'phone' => $phoneQr,
            'date_created' => $date_created
        ], [
            'phone' => 'required',
            'date_created.*' => 'required|date_format:Y-m-d H:i:s'
        ])->errors();
    }

    /**
     * @param array $thongke
     * @param array $total
     * @return array
     */
    public function extracted_switch(array $thongke, array $total): array
    {
        switch ($thongke['0_2']) {
            case  0 :
                $total['0_2']['0_']++;
                break;
            case  1 :
            case  2 :
                $total['0_2']['1_2']++;
                break;
            case  3 :
            case  4 :
                $total['0_2']['3_4']++;
                break;
            default :
                $total['0_2']['5_']++;
                break;
        }

        switch ($thongke['3_5']) {
            case  0 :
                $total['3_5']['0_']++;
                break;
            case  1 :
            case  2 :
                $total['3_5']['1_2']++;
                break;
            case  3 :
            case  4 :
                $total['3_5']['3_4']++;
                break;
            default :
                $total['3_5']['5_']++;
                break;
        }

        switch ($thongke['6_7']) {
            case  0 :
                $total['6_7']['0_']++;
                break;
            case  1 :
            case  2 :
                $total['6_7']['1_2']++;
                break;
            case  3 :
            case  4 :
                $total['6_7']['3_4']++;
                break;
            default :
                $total['6_7']['5_']++;
                break;
        }
        return $total;
    }
}
