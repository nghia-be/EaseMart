<?php

namespace App\DataTables\Admin;

use App\DataTables\BuilderDatatables;
use App\Models\Group_Module;
use App\Models\User;

class GroupModuleDataTable extends BuilderDatatables
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('group_module_name', function ($row) {
                return '
                    <a href="'.route('groupmodule.edit', $row->id).'">'.$row->group_module_name.'</a>
                    <div class="row-options">
                        <a href="'.route('groupmodule.edit', $row->id).'">View</a> |
                        <a href="#" data-id="'.$row->id.'" onclick="dialogConfirmWithAjax(deleteGroupModule, this)" class="text-danger">Remove</a>
                    </div>
                ';
            })
            ->editColumn('checkbox',function($row){
                return '<div class="checkbox"><input type="checkbox" value="'.$row->event_id.'"><label></label></div>';
            })
            ->editColumn('status', function ($query) {
                if ($query->status == 1) {
                    return '<span style="color: rgb(0,86,13)" class="badge border border-blue" >Active</span>';
                } else {
                    return '<span style="color: #9f3535" class="badge border border-blue" >Inactive</span>';
                }
            })
            ->editColumn('created_by',function($row){
                return $row->createdBy->email ?? $row->created_by;
            })
            ->rawColumns(['group_module_name', 'checkbox', 'status']);
    }

    public function query(Group_Module $model)
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
            'group_module_name' => [
                'title' => 'Tên',
            ],
            'status' => [
                'title' => 'Trạng thái',
            ],
            'created_at' => [
                'title' => 'Ngày tạo',
            ],
            'created_by' => [
                'title' => 'Người tạo',
            ]
        ];
    }
}
