<?php

namespace App\DataTables\Admin;

use App\DataTables\BuilderDatatables;
use App\Models\Roles;
use App\Models\User;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Html\Column;

class RolesDataTable extends BuilderDatatables
{
    protected $hasCheckbox = false;
    protected $orderBy = 0;
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('action', function ($row) {
                return '
                    <div class="tw-flex tw-items-center tw-space-x-3">
                        <a href="'.route('role.edit', [$row->id]).'" id="detail" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                                            <i class="fa-regular fa-pen-to-square fa-lg"></i>
                        </a>
                        <a href="#" data-id="'.$row->id.'" onclick="dialogConfirmWithAjax(deleteRoles, this)" data-name="' . $row->name . '"class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700" >
                                            <i class="fa-regular fa-trash-can fa-lg"></i>
                        </a>
                    </div>
                ';
            })
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('Y-m-d');
            })
            ->rawColumns(['action']);
    }

    public function query(Role $model)
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
            'name' => [
                'title' => 'Tên',
            ],
            'created_at' => [
                'title' => 'Ngày tạo',
            ],
            Column::computed('action')->sortable(false)
                ->searching(false)
        ];
    }
}
