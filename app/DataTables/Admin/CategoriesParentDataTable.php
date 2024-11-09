<?php

namespace App\DataTables\Admin;

use App\Models\Group_Module;
use App\Models\CategoriesParent;
use Yajra\DataTables\Html\Column;
use App\DataTables\BuilderDatatables;

class CategoriesParentDataTable extends BuilderDatatables
{
    protected $ajaxUrl = ['data' => 'function(d) { console.log(d);d.table = "detail"; }'];
    protected $hasCheckbox = false;
    protected $orderBy = 0;
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('categories_parents_name', function ($row) {
                return '
                    <a href="#" data-id="'.$row->id.'" onclick="detailCategoriesParent(this)">'.$row->categories_parents_name.'</a>
                    <div class="row-options">
                        <a href="#" data-id="'.$row->id.'" onclick="detailCategoriesParent(this)">View</a> |
                        <a href="#" data-id="'.$row->id.'" onclick="dialogConfirmWithAjax(deleteCategoriesParent, this)" class="text-danger">Remove</a>
                    </div>
                ';
            })
            ->editColumn('created_by',function($row){
                return $row->createdBy->email ?? $row->created_by;
            })

            ->editColumn('image', function($row){
                $url = env('APP_URL');
                return $row->image ? '<img src="'.$url.$row->image.'" width="50px">' : 'Không có image';
            }
            )
            ->editColumn('status', function ($query) {
                if ($query->status == 1) {
                    return '<span style="color: rgb(0,86,13)" class="badge border border-blue" >Active</span>';
                } else {
                    return '<span style="color: #9f3535" class="badge border border-blue" >Inactive</span>';
                }
            })
            ->editColumn('created_by',function($row){
                return $row->createdBy->email ?? $row->created_by;
            })
            ->editColumn('action', function ($row) {
                $s = $row->status ? 'checked' : '';
                return '
                <form>
                        <div class="onoffswitch" data-toggle="tooltip">
                        <input type="checkbox" data-id="' . $row->id . '" onclick="dialogConfirmWithAjaxCategoriesParent(changeStatusCategoriesParent, this)" class="onoffswitch-checkbox" id="' . $row->id . '" '.$s.'>
                        <label class="onoffswitch-label" for="' . $row->id . '"></label>
                        </div><span class="hide">Yes</span>
                </form>
                
                    ';
            })
            ->rawColumns(['categories_parents_name', 'image', 'action', 'status']);
           ;
    }

    public function query(CategoriesParent $model)
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
            'categories_parents_name' => [
                'title' => 'Tên',
            ],
            'image' => [
                'title' => 'Hình ảnh',
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
