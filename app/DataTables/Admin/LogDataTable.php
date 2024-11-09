<?php

namespace App\DataTables\Admin;

use App\DataTables\BuilderDatatables;
use App\Models\Log_activities;
use App\Models\User;

class LogDataTable extends BuilderDatatables
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('checkbox',function($row){
                return '<div class="checkbox"><input type="checkbox" value="'.$row->event_id.'"><label></label></div>';
            })
            ->editColumn('user_id',function($row){
                return $row->user->email;
            })
            ->rawColumns(['checkbox']);
    }

    public function query(Log_activities $model)
    {
        return $model->newQuery();
    }

    public function columns(): array
    {
        return [
            'id' => [
                'title' => 'ID',
                'width' => '20px',
            ],
            'user_id' => [
                'title' => 'Tên',
            ],
            'ip' => [
                'title' => 'IP',
            ],
            'param' => [
                'title' => 'Param',
            ],
            'url' => [
                'title' => 'URL',
            ],
            'method' => [
                'title' => 'Method',
            ],
            'agent' => [
                'title' => 'Agent',
            ],
            'created_at' => [
                'title' => 'Ngày tạo',
            ]
        ];
    }
}
