<?php

namespace App\DataTables\Admin;

use App\DataTables\BuilderDatatables;
use App\Models\Settings;
use App\Models\User;
use Yajra\DataTables\Html\Column;

class UrlSettingDataTable extends BuilderDatatables
{
    protected $ajaxUrl = ['data' => 'function(d) { console.log(d);d.table = "detail"; }'];
    protected $hasCheckbox = false;
    public function ajax()
    {
        return datatables()
            ->collection($this->query())
            ->editColumn('status', function ($query) {
                if ($query->status == 1) {
                    return '<span style="color: rgb(0,86,13)" class="badge border border-blue" >Active</span>';
                } else {
                    return '<span style="color: #9f3535" class="badge border border-blue" >Inactive</span>';
                }
            })
            ->addIndexColumn()
            ->rawColumns(['status', ''])
            ->make();
    }

    public function query()
    {
        $model = Settings::where('name', 'uri_config')->get()->pluck('value', 'name');
        return json_decode($model['uri_config'], false);
    }

    public function columns(): array
    {
        return [
            'DT_RowIndex' => [
                'title'=>'STT'
            ],
            'name' => [
                'title' => 'Tên URL',
            ],
            'uri' => [
                'title' => 'URI',
            ],
            'status' => [
                'title' => 'Trạng thái',
            ]
        ];
    }
}
