<?php

namespace App\DataTables\Admin;


use App\Models\Units;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use App\DataTables\BuilderDatatables;


class UnitsDataTable extends BuilderDatatables
{
    protected $ajaxUrl = ['data' => 'function(d) { console.log(d);d.table = "detail"; }'];
    protected $hasCheckbox = false;
    protected $orderBy = 0;
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('unit_code', function ($row) {
                return '
                    <a href="#" data-id="'.$row->id.'" onclick="detailUnits(this)">'.$row->unit_code.'</a>
                    <div class="row-options">
                        <a href="#" data-id="'.$row->id.'" onclick="detailUnits(this)">View</a> |
                        <a href="#" data-id="'.$row->id.'" onclick="dialogConfirmWithAjaxUnits(deleteUnits, this)" class="text-danger">Remove</a>
                    </div>
                ';
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
                        <input type="checkbox" data-id="' . $row->id . '" onclick="dialogConfirmWithAjaxUnits(changeStatusUnits, this)" class="onoffswitch-checkbox" id="' . $row->id . '" '.$s.'>
                        <label class="onoffswitch-label" for="' . $row->id . '"></label>
                        </div><span class="hide">Yes</span>
                </form>
                
                    ';
            })
            ->rawColumns(['unit_code','action','checkbox','status']);
    }

    public function query(Units $model)
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
            'unit_code' => [
                'title' => 'Mã đơn vị',
            ],
            'unit_name' => [
                'title' => 'Tên đơn vị',
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
