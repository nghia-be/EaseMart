<?php

namespace App\DataTables\Admin;

use App\Models\Group_Module;
use App\Models\Customers;
use Yajra\DataTables\Html\Column;
use App\DataTables\BuilderDatatables;

class CustomersDataTable extends BuilderDatatables
{
    protected $ajaxUrl = ['data' => 'function(d) { console.log(d);d.table = "detail"; }'];
    protected $hasCheckbox = false;
    protected $orderBy = 0;
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('name', function ($row) {
                return '
                    <a href="#" data-id="'.$row->id.'" onclick="detailCustomers(this)">'.$row->name.'</a>
                    <div class="row-options">
                        <a href="#" data-id="'.$row->id.'" onclick="detailCustomers(this)">View</a> |
                        <a href="#" data-id="'.$row->id.'" onclick="dialogConfirmWithAjaxCustomers(deleteCustomers, this)" class="text-danger">Remove</a>
                    </div>
                ';
            })

            ->editColumn('image', function($row){
                $url = env('APP_URL');
                return $row->image ? '<img src="'.$url.$row->image.'" width="50px">' : 'No image';
            }
            )
            ->editColumn('status', function ($query) {
                if ($query->status == 1) {
                    return '<span style="color: rgb(0,86,13)" class="badge border border-blue" >Active</span>';
                } else {
                    return '<span style="color: #9f3535" class="badge border border-blue" >Inactive</span>';
                }
            })
            ->editColumn('action', function ($row) {
                $s = $row->status ? 'checked' : '';
                return '
                <form>
                        <div class="onoffswitch" data-toggle="tooltip">
                        <input type="checkbox" data-id="' . $row->id . '" onclick="dialogConfirmWithAjaxCustomers(changeStatusCustomers, this)" class="onoffswitch-checkbox" id="' . $row->id . '" '.$s.'>
                        <label class="onoffswitch-label" for="' . $row->id . '"></label>
                        </div><span class="hide">Yes</span>
                </form>
                
                    ';
            })
            ->rawColumns(['name', 'image', 'action', 'status']);
           ;
    }

    public function query(Customers $model)
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
            'image' => [
                'title' => 'Hình ảnh',
            ],
            'email' => [
                'title' => 'Email',
            ],
            'address' => [
                'title' => 'Địa chỉ',
            ],
            'gender' => [
                'title' => 'Giới tính',
            ],
            'status' => [
                'title' => 'Trạng thái',
            ],
            'created_at' => [
                'title' => 'Ngày tạo',
            ],
            
            Column::computed('action')->sortable(false)
                ->searching(false)
                ->title('Ẩn / Hiện')
                ->width('100')
                ->addClass('text-center'),
        ];
    }
}
