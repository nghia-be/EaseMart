<?php

namespace App\DataTables\Admin;


use App\Models\Stock;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use App\DataTables\BuilderDatatables;

class StockDataTable extends BuilderDatatables
{
    protected $ajaxUrl = ['data' => 'function(d) { console.log(d);d.table = "detail"; }'];
    protected $hasCheckbox = false;
    protected $orderBy = 0;
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('product_id', function ($row) {
                $productName = $row->product ? $row->product->name : 'N/A';
                return '
                <a href="#" title="' . $productName . '">' . 
            Str::limit($productName, 20, '...') . 
        '</a>
                    <div class="row-options">
                        <a href="#" data-id="'.$row->id.'" onclick="detailProductUnits(this)">View</a> |
                        <a href="#" data-id="'.$row->id.'" onclick="dialogConfirmWithAjax(deleteProductUnits, this)" class="text-danger">Remove</a>
                    </div>
                ';
            })
            ->editColumn('unit_code', function ($row) {
                return $row->unit->unit_name ?? 'N/A';
            })
            ->editColumn('warehouse_code', function ($row) {
                return $row->warehouse->name ?? 'N/A';
            })
            ->rawColumns(['product_id','unit_code']);
           ;
    }

    public function query(Stock $model)
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
            'product_id' => [
                'title' => 'Sản phẩm',
            ],
            'unit_code' => [
                'title' => 'Đơn vị',
            ],
            'quantity' => [
                'title' => 'Số lượng',
            ],
            'warehouse_code' => [
                'title' => 'Kho hàng',
            ],
        ];
    }
}
