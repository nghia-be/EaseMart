<?php

namespace App\DataTables\Admin;

use App\Models\Products;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use App\DataTables\BuilderDatatables;

class ProductsDataTable extends BuilderDatatables
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
                    <a href="' . route('products.edit', $row->id) . '" class="product-name" title="' . $row->name . '">' . 
            Str::limit($row->name, 20, '...') . 
        '</a>
                    <div class="row-options">
                        <a href="#" data-id="'.$row->id.'" onclick="detailBrand(this)">View</a> |
                        <a href="#" data-id="'.$row->id.'" onclick="dialogConfirmWithAjax(deleteProducts, this)" class="text-danger">Remove</a>
                    </div>
                ';
            })
            ->editColumn('image', function ($row) {
               
                $imagePath = $row->primaryImagePath();
                $url = env('APP_URL');
                if ($imagePath) {
                    return '<img src="'.$url.$imagePath. '" alt="'.$row->alt_text.'" width="50" height="50">';
                } else {
                    return 'No image';
                }
            })
            ->editColumn('price', function ($row) {
                return number_format($row->price, 0, ',', '.') . 'đ';
            })
            ->editColumn('price_sale', function ($row) {
                return number_format($row->price_sale, 0, ',', '.') . 'đ';
            })
            ->editColumn('checkbox', function ($row) {
                return '<div class="checkbox"><input type="checkbox" value="' . $row->event_id . '"><label></label></div>';
            })
            ->editColumn('categories_id', function ($row) {
                return $row->categories->categories_name ?? 'N/A';
            })
            ->editColumn('brand_id', function ($row) {
                return $row->brand->name ?? 'N/A';
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
                        <input type="checkbox" data-id="' . $row->id . '" onclick="dialogConfirmWithAjaxchangeStatusBrand(changeStatusBrand, this)" class="onoffswitch-checkbox" id="' . $row->id . '" '.$s.'>
                        <label class="onoffswitch-label" for="' . $row->id . '"></label>
                        </div><span class="hide">Yes</span>
                </form>
                
                    ';
            })
            
            ->rawColumns(['name','checkbox','brand_id','price','image','price_sale','categories_id','status','action']);
    }

    public function query(products $model)
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
            'price' => [
                'title' => 'Giá',
            ],
            'price_sale' => [
                'title' => 'Giá Giảm',
            ],
            'status' => [
                'title' => 'Trạng thái',
            ], 
            'categories_id' => [
                'title' => 'Danh mục',
            ], 
            'brand_id' => [
                'title' => 'Thương hiệu',
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
