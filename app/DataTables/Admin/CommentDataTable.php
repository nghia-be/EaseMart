<?php

namespace App\DataTables\Admin;


use App\Models\Comment;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use App\DataTables\BuilderDatatables;


class CommentDataTable extends BuilderDatatables
{
    // protected $ajaxUrl = ['data' => 'function(d) { console.log(d);d.table = "detail"; }'];
    // protected $hasCheckbox = false;
    // protected $orderBy = 0;
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('customer_id', function ($row) {
                return '
                    <a href="#" data-id="'.$row->id.'" onclick="detailComment(this)">'.$row->customer->name.'</a>
                    <div class="row-options">
                        <a href="#" data-id="'.$row->id.'" onclick="detailComment(this)">View</a> |
                        
                        <a href="#" data-id="'.$row->id.'" onclick="dialogConfirmWithAjax(deleteComment, this)" class="text-danger">Remove</a>
                    </div>
                ';
            })
            ->editColumn('product_id', function ($row) {
                $productName = $row->product ? $row->product->name : 'N/A';
                return '
                <a href="#" title="' . $productName . '">' . 
            Str::limit($productName, 20, '...') . 
        '</a>
                ';
            })
            ->editColumn('rating', function ($row) { 
                $stars = '';
                $rating = min(5, max(0, $row->rating)); 
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $rating) {
                        $stars .= '<i class="fa fa-star text-warning"></i>';
                    } else {
                        $stars .= '<i class="fa fa-star-o text-muted"></i>';
                    }
                }
                return '<div class="rating">' . $stars . '</div>';
            })
            ->editColumn('checkbox', function ($row) {
                return '<div class="checkbox"><input type="checkbox" value="' . $row->event_id . '"><label></label></div>';
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
                        <input type="checkbox" data-id="' . $row->id . '" onclick="dialogConfirmWithAjax(changeStatusComment, this)" class="onoffswitch-checkbox" id="' . $row->id . '" '.$s.'>
                        <label class="onoffswitch-label" for="' . $row->id . '"></label>
                        </div><span class="hide">Yes</span>
                </form>
                
                    ';
            })
            ->rawColumns(['customer_id','product_id','rating','action','checkbox','status']);
    }

    public function query(Comment $model)
    {
        return $model->newQuery()
        ->select('comments.*', 'customers.name as customer_name')
        ->join('customers', 'comments.customer_id', '=', 'customers.id');
    }

    public function columns(): array
    {
        return [
            // 'id' => [
            //     'title' => 'ID',
            //     'width' => '20px',
            // ],
            'customer_id' => [
                'title' => 'Khách hàng',
                'searchable' => true,   
            ],
            'product_id' => [
                'title' => 'Sản phẩm',
                
            ],  
            'rating' => [
                'title' => 'Đánh giá',
                
            ],
            'content' => [
                'title' => 'Nội dung',
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
