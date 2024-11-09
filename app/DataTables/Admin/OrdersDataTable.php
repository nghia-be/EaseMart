<?php

namespace App\DataTables\Admin;

use App\Models\Order;
use App\Models\Status;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use App\DataTables\BuilderDatatables;

class OrdersDataTable extends BuilderDatatables
{
    // protected $ajaxUrl = ['data' => 'function(d) { console.log(d);d.table = "detail"; }'];
    // protected $hasCheckbox = false;
    // protected $orderBy = 0;
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            
            ->editColumn('order_code', function ($row) {
                return '
                     <a href="#" data-id="'.$row->id.'" onclick="detailBrand(this)">'.$row->order_code.'</a>
                    <div class="row-options">
                        <a href="#" data-id="'.$row->id.'" onclick="detailBrand(this)">View</a> |
                        <a href="#" data-id="'.$row->id.'" onclick="dialogConfirmWithAjax(deleteBrand, this)" class="text-danger">Remove</a>
                    </div>
                ';
            })
            
            ->editColumn('customers_id', function ($row) {
                return $row->customers->name ?? 'N/A';
            })
            ->editColumn('order_status_id', function ($row) {
                // Lấy danh sách các trạng thái với `type = 'order'`
                $statuses = Status::where('type', 'order')->get();
                
                // Tạo các tùy chọn cho dropdown với màu sắc tùy thuộc vào status_id
                $options = '';
                foreach ($statuses as $status) {
                    // Kiểm tra trạng thái để áp dụng màu sắc cho từng option
                    $selected = $row->order_status_id == $status->id ? 'selected' : '';
                    $color = ''; // Màu mặc định
                    
                    // Gán màu sắc cho từng trạng thái
                    switch ($status->id) {
                        case 9:
                            $color = 'color: yellow;'; // Màu vàng
                            break;
                        case 11:
                            $color = 'color: deepskyblue;'; // Màu xanh nước biển
                            break;
                        case 12:
                            $color = 'color: lightgreen;'; // Màu xanh lá
                            break;
                        case 13:
                            $color = 'color: red;'; // Màu đỏ
                            break;
                        default:
                            $color = 'color: black;'; // Màu mặc định
                            break;
                    }
            
                    // Tạo option với màu sắc được áp dụng vào text
                    $options .= "<option value='{$status->id}' style='$color' $selected>{$status->status_name}</option>";
                }
            
                // Tạo dropdown với sự kiện `onchange` để gọi AJAX
                return "
                   <select onchange='updateOrderStatus({$row->id}, this.value, {$row->order_status_id})' data-order-id='{$row->id}' style='$color'>
            $options
        </select>
                ";
            })
            
            
            
            ->editColumn('checkbox', function ($row) {
                return '<div class="checkbox"><input type="checkbox" value="' . $row->event_id . '"><label></label></div>';
            })
            ->editColumn('total_amount', function ($row) {
                return number_format($row->total_amount, 0, ',', '.') . 'đ';
            })
           
        
            
            ->rawColumns(['order_code','checkbox','customers_id','order_status_id','total_amount']);
    }

    public function query(Order $model)
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
            'order_code' => [
                'title' => 'Mã đơn hàng',
            ],
            'customers_id' => [
                'title' => 'Người mua',
            ],
            'order_status_id' => [
                'title' => 'Trạng thái',
            ],
            'total_amount' => [
                'title' => 'Tổng đơn',
            ]
        ];
    }
}
