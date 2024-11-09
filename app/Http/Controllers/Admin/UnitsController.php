<?php

namespace App\Http\Controllers\Admin;

use App\Models\Units;
use Illuminate\Http\Request;
use App\Http\Traits\DataTrait;
use Illuminate\Validation\Rule;
use App\DataTables\Admin\UnitsDataTable;
use App\Http\Controllers\BaseController;


class UnitsController extends BaseController
{
    use DataTrait;
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Danh sách đơn vị';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UnitsDataTable $dataTable, Request $request)
    {   
        return $dataTable->render('units.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_code' => 'required|string|max:255|unique:units',
            'unit_name' => 'required|string|max:255',
        ]);
        Units::create($request->all());
        $this->addToLog(request());
        return response(['success' => 'success', 'message'=> 'Thêm mới thành công!']);
    }

    public function show(Request $request)
    {
        $unit = Units::findOrFail($request->id);
        return response(['data' => $unit]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'unit_code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('units')->ignore($request->id), // Nếu có id, sẽ bỏ qua khi cập nhật
            ],
            'unit_name' => 'required|string|max:255',
        ]);
        $unit = Units::findOrFail($request->id);
        $unit->update($request->all());
        $this->addToLog($request);
        return response(['success' => 'success', 'message'=> 'Sửa thành công!']);
    }

    public function destroy(Request $request)
    {
        $unit = Units::findOrFail($request->id);
        $unit->delete();
        $this->addToLog(request());
        return response()->json(['message' => 'Xóa thành công!']);
    }
    public function changeStatus(Request $request)
    {
        $unit = Units::findOrFail($request->id);
        $unit->status == 0 ? $unit->status =1 : $unit->status = 0;
        $unit->save();
        $this->addToLog(request());
        return response()->json(['message' => 'Thay đổi thành công!']);
    }
}
