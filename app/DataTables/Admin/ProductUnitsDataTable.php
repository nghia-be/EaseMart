<?php

namespace App\DataTables\Admin;


use App\Models\ProductUnits;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use App\DataTables\BuilderDatatables;


class ProductUnitsDataTable extends BuilderDatatables
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
            ->editColumn('image', function ($row) {
                $image = $row->product->primaryImage;
                $url = env('APP_URL'); 
                if ($image) {
                    return '<img src="'.$url.$image->image_path.'" alt="'.$row->alt_text.'" width="50" height="50">';
                } else {
                    return 'No image';
                }
            })
            
            ->editColumn('checkbox', function ($row) {
                return '<div class="checkbox"><input type="checkbox" value="' . $row->event_id . '"><label></label></div>';
            })
            // ->editColumn('product_id', function ($row) {
            //     return $row->product->name ?? 'N/A';
            // })
            ->editColumn('unit_code', function ($row) {
                return $row->unit->unit_name ?? 'N/A';
            })
            ->editColumn('price', function ($row) {
                return number_format($row->price, 0, ',', '.') . 'đ';
            })
            ->editColumn('price_sale', function ($row) {
                return number_format($row->price_sale, 0, ',', '.') . 'đ';
            })
            ->editColumn('level', function ($row) {
                switch ($row->level) {
                    case 1:
                        return 'Cao';
                    case 2:
                        return 'Trung Bình';
                    case 3:
                        return 'Thấp Nhất';
                    default:
                        return 'Không xác định'; 
                }
            })
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
                        <input type="checkbox" data-id="' . $row->id . '" onclick="dialogConfirmWithAjax(changeStatus, this)" class="onoffswitch-checkbox" id="' . $row->id . '" '.$s.'>
                        <label class="onoffswitch-label" for="' . $row->id . '"></label>
                        </div><span class="hide">Yes</span>
                </form>
                
                    ';
            })
            ->rawColumns(['image','action','checkbox','price','price_Sale','status','product_id','level','unit_code']);
    }

    public function query(ProductUnits $model)
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
            'image' => [
                'title' => 'Hình ảnh',
            ], 
            'product_id' => [
                'title' => 'Sản phẩm',
            ],  
            'unit_code' => [
                'title' => 'Đơn vị',
            ],
            'price' => [
                'title' => 'Giá',
            ],
            'price_sale' => [
                'title' => 'Giá giảm',
            ],
            'level' => [
                'title' => 'Cấp độ',
            ], 
            'exchangrate' => [
                'title' => 'Tỉ lệ quy đổi',
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
