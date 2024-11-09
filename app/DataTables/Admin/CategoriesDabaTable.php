<?php

namespace App\DataTables\Admin;


use App\Models\Categories;
use App\DataTables\BuilderDatatables;


class CategoriesDabaTable extends BuilderDatatables
{
    protected $ajaxUrl = ['data' => 'function(d) { console.log(d);d.table = "detail"; }'];
    protected $hasCheckbox = false;
    protected $orderBy = 0;
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('categories_name', function ($row) {
                return '
                    <a href="#" data-id="'.$row->id.'" onclick="detailCategories(this)">'.$row->categories_name.'</a>
                    <div class="row-options">
                        <a href="#" data-id="'.$row->id.'" onclick="detailCategories(this)">View</a> |
                        <a href="#" data-id="'.$row->id.'" onclick="dialogConfirmWithAjax(deleteCategories, this)" class="text-danger">Remove</a>
                    </div>
                ';
            })
            ->editColumn('categories_parent_id', function ($row) {
                return $row->parent->categories_parents_name ?? 'N/A';
            })
            ->editColumn('image', function($row){
                $url = env('APP_URL');
                return $row->image ? '<img src="'.$url.$row->image.'" width="50px">' : 'Không có image';
            })
            ->editColumn('created_at',function($row){
                return $row->createdBy->email ?? $row->created_at;
            })
            ->rawColumns(['categories_name','image']);
    }

    public function query(Categories $model)
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
            'categories_name' => [
                'title' => 'Tên',
            ],
            'image' => [
                'title' => 'Hình ảnh',
            ],  
            'categories_parent_id' => [
                'title' => 'Danh mục cha',
            ],
            'updated_at' => [
                'title' => 'Ngày cập nhập',
            ],
            'created_at' => [
                'title' => 'Ngày tạo',
            ]
        ];
    }
}
