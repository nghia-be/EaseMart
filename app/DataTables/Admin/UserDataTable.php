<?php

namespace App\DataTables\Admin;

use App\DataTables\BuilderDatatables;
use App\Models\User;
use Yajra\DataTables\Html\Column;

class UserDataTable extends BuilderDatatables
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('name', function ($row) {
                return '
                    <a href="' . route('user.edit', $row->id) . '">' . $row->name . '</a>
                    <div class="row-options">
                        <a href="' . route('user.edit', $row->id) . '">View</a> |
                        <a href="#" data-id="' . $row->id . '" onclick="dialogConfirmWithAjax(deleteUser, this)" class="text-danger">Remove</a>
                    </div>
                ';
            })
            ->editColumn('role_id', function ($row) {
                $user = User::find($row->id);
                return $user->getRoleNames()->first();
            })
            ->editColumn('checkbox', function ($row) {
                return '<div class="checkbox"><input type="checkbox" value="' . $row->event_id . '"><label></label></div>';
            })
            ->editColumn('action', function ($row) {
                return '
                    <div class="tw-flex tw-items-center tw-space-x-3">
                        <a href="'.route('login').'"
                        onclick="event.preventDefault(); document.getElementById(`login-user-'.$row->id.'`).submit();"
                        class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                                            <i class="fa fa-sign-in fa-lg"></i>
                        </a>
                        <form id="login-user-'.$row->id.'" action="'.route('user.login').'" method="POST" class="d-none">
                            '.csrf_field().'
                            @'.$row->name.'
                            <input type="text" class="hide" name="id" value="'.$row->id.'">
                        </form>
                    </div>';
            })
            ->rawColumns(['name', 'checkbox', 'action']);
    }

    public function query(User $model)
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
            'email' => [
                'title' => 'Email',
            ],
            'role_id' => [
                'title' => 'Quyền Hạn',
            ],
            'created_at' => [
                'title' => 'Ngày tạo',
            ],
            Column::computed('action')->sortable(false)
                ->searching(false)
                ->title('Login with')
                ->width('100')
                ->addClass('text-center'),
        ];
    }
}
