<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\WarehouseDataTable;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Http\Traits\DataTrait;
use App\Models\Warehouse;
use Spatie\Permission\Models\Permission;

class WarehouseController extends BaseController
{
    use DataTrait;
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Danh sách kho hàng';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(WarehouseDataTable $dataTable, Request $request)
    {
        return $dataTable->render('warehouse.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:warehouse|max:255', 
            'email' => 'required|email|max:255', 
            'phone' => [
                'required',
                'regex:/^(\+?\d{1,3})?(\d{10,12})$/'      
            ],
            'address' => 'required|string|max:255'  
                ]);
        Warehouse::create($request->all());
        $this->addToLog(request());
        return response(['success' => 'success', 'message'=> 'Thêm kho thành công!']);
    }

    public function show(Request $request)
    {
        $data = Warehouse::findOrFail($request->id);
        return response(['data' => $data]); 
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:warehouse|max:255', 
            'email' => 'required|email|max:255', 
            'phone' => [
                'required',
                'regex:/^(\+?\d{1,3})?(\d{10,12})$/'      
            ],
            'address' => 'required|string|max:255'  
        ]);
        $warehouse = Warehouse::findOrFail($request->id);
        $warehouse->update($request->all());
        $this->addToLog($request);
        return response(['success' => 'success', 'message'=> 'Cập nhập thành công!']);
    }

    public function destroy(Request $request)
    {
        $warehouse = Warehouse::findOrFail($request->id);
        $warehouse->delete();
        $this->addToLog(request());
        return response()->json(['message' => 'Xóa thành công!']);
    }
}
