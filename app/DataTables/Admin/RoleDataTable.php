<?php

namespace App\DataTables\Admin;

use App\DataTables\BuilderDatatables;
use App\Models\Roles;
use App\Models\User;

class RoleDataTable extends BuilderDatatables
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('role_name', function ($row) {
                return '
                    <a href="'.route('roles.edit', $row->id).'">'.$row->role_name.'</a>
                    <div class="row-options">
                        <a href="'.route('roles.edit', $row->id).'">View</a> |
                        <a href="#" data-id="'.$row->id.'" onclick="dialogConfirmWithAjax(deleteRoles, this)" class="text-danger">Remove</a>
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
            ->rawColumns(['role_name', 'checkbox', 'status']);
    }

    public function query(Roles $model)
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
            'role_name' => [
                'title' => 'Tên',
            ],
            'status' => [
                'title' => 'Trạng thái',
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
