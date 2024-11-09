<?php

namespace App\DataTables\Admin;

use App\Models\Supplier;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use App\DataTables\BuilderDatatables;

class SupplierDataTable extends BuilderDatatables
{
    // protected $ajaxUrl = ['data' => 'function(d) { console.log(d);d.table = "detail"; }'];
    // protected $hasCheckbox = false;
    // protected $orderBy = 0;
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            
            ->editColumn('name', function ($row) {
                return '
                    <a href="' . route('supplier.edit', $row->id) . '" class="name" title="' . $row->name . '">' . 
            Str::limit($row->name, 20, '...') . 
        '</a>
                    <div class="row-options">
                        <a href="#" data-id="'.$row->id.'" onclick="detailSupplier(this)">View</a> |
                        <a href="#" data-id="'.$row->id.'" onclick="dialogConfirmWithAjax(deleteSupplier, this)" class="text-danger">Remove</a>
                    </div>
                ';
            })
            ->editColumn('checkbox', function ($row) {
                return '<div class="checkbox"><input type="checkbox" value="' . $row->event_id . '"><label></label></div>';
            })


            
            ->rawColumns(['name','checkbox']);
    }

    public function query(Supplier $model)
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
            'phone' => [
                'title' => 'Số điện thoại',
            ],
            'address' => [
                'title' => 'Địa chỉ',
            ],
            'created_at' => [
                'title' => 'Ngày tạo',
            ],

        ];
    }
}
