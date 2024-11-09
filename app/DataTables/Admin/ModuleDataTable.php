<?php

namespace App\DataTables\Admin;

use App\DataTables\BuilderDatatables;
use App\Models\Modules;
use App\Models\User;

class ModuleDataTable extends BuilderDatatables
{
    protected $ajaxUrl = ['data' => 'function(d) { console.log(d);d.table = "detail"; }'];
    protected $hasCheckbox = false;
    protected $orderBy = 0;
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('module_name', function ($row) {
                return '
                    <a href="#" data-id="'.$row->id.'" onclick="detailModules(this)">'.$row->module_name.'</a>
                    <div class="row-options">
                        <a href="#" data-id="'.$row->id.'" onclick="detailModules(this)">View</a> |
                        <a href="#" data-id="'.$row->id.'" onclick="dialogConfirmWithAjax(deleteModules, this)" class="text-danger">Remove</a>
                    </div>
                ';
            })
            ->editColumn('created_by',function($row){
                return $row->createdBy->email ?? $row->created_by;
            })
            ->rawColumns(['module_name']);
    }

    public function query(Modules $model)
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
            'module_name' => [
                'title' => 'Tên',
            ],
            'uri' => [
                'title' => 'URL',
            ],
            'icon' => [
                'title' => 'Tên',
            ],
            'group_module_id' => [
                'title' => 'Group Module',
            ],
            'created_by' => [
                'title' => 'Người tạo',
            ],
            'created_at' => [
                'title' => 'Ngày tạo',
            ]
        ];
    }
}
