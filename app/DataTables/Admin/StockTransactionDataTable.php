<?php

namespace App\DataTables\Admin;

use Illuminate\Support\Str;
use App\Models\Group_Module;
use App\Models\StockTransaction;
use Yajra\DataTables\Html\Column;
use App\DataTables\BuilderDatatables;

class StockTransactionDataTable extends BuilderDatatables
{
    protected $ajaxUrl = ['data' => 'function(d) { console.log(d);d.table = "detail"; }'];
    protected $hasCheckbox = false;
    protected $orderBy = 0;
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
    
            ->editColumn('transaction_code', function ($row) {
                return '
                    <a href="#" data-id="'.$row->id.'" onclick="detailTransaction(this)">'.$row->transaction_code.'</a>
                    <div class="row-options">
                        <a href="#" data-id="'.$row->id.'" onclick="detailTransaction(this)">View</a> |
                    </div>
                ';
            })
            ->editColumn('warehouse_code', function ($row) {
                return $row->warehouse->name ?? 'N/A';
            })
            ->editColumn('created_by',function($row){
                return $row->createdBy->name ?? $row->created_by;
            })
            ->editColumn('transaction_type', function ($row) {
                        $transactionTypeText = $row->transaction_type == 1 
                ? '<span class="text-primary">Nhập tự do</span>' 
                : '<span class="text-danger">Xuất tự do</span>';

            return '
                ' . $transactionTypeText . '
            ';
            })
            ->rawColumns(['transaction_code','transaction_type','warehouse_code','created_by']);
           ;
    }

    public function query(StockTransaction $model)
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
            // 'product_id' => [
            //     'title' => 'Mã giao dịch',
            // ],
            'transaction_code' => [
                'title' => 'Mã giao dịch',
            ],
            'transaction_type' => [
                'title' => 'Loại giao dịch',
            ],
            'warehouse_code' => [
                'title' => 'Kho hàng',
            ],
            'note' => [
                'title' => 'Ghi chú',
            ],
            'created_at' => [
                'title' => 'Ngày tạo',
            ],
            'created_by' => [
                'title' => 'Người tạo',
            ],
        ];
    }
}
