<?php

namespace App\Http\Controllers\Admin;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Traits\DataTrait;
use App\Http\Controllers\BaseController;
use Illuminate\Validation\Rule;
use App\DataTables\Admin\SupplierDataTable;

class SupplierController extends BaseController
{   
    use DataTrait;
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Danh sách nhà cung cấp';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SupplierDataTable $dataTable)
    {
        return $dataTable->render('supplier.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:supplier|max:255', 
            'email' => 'required|email|max:255', 
            'phone' => [
                'required',
                'regex:/^(\+?\d{1,3})?(\d{10,12})$/'      
            ],
            'address' => 'required|string|max:255'  
                ]);
        Supplier::create($request->all());
        $this->addToLog(request());
        return response(['success' => 'success', 'message'=> 'Thêm nhà cung cấp thành công!']);
    }

    public function show(Request $request)
    {
        $supplier = Supplier::findOrFail($request->id);
        return response(['data' => $supplier]);
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => [
                    'required',
                    'max:255',
                    Rule::unique('supplier')->ignore($id), // Chú ý sử dụng $id được truyền vào
                ],
                'email' => 'required|email|max:255', 
                'phone' => [
                    'required',
                    'regex:/^(\+?\d{1,3})?(\d{10,12})$/'      
                ],
                'address' => 'required|string|max:255'  
            ]);
            
            $supplier = Supplier::findOrFail($id);
            $supplier->update($request->all());
            $this->addToLog($request);
            return response(['success' => 'success', 'message'=> 'Cập nhập thành công!']);
        } catch (\Exception $e) {
            // Hiển thị lỗi để kiểm tra
            dd($e->getMessage());
        }
    }
    

    public function destroy(Request $request)
    {
        $supplier = Supplier::findOrFail($request->id);
        $supplier->delete();
        $this->addToLog(request());
        return response()->json(['message' => 'Xóa thành công!']);
    }
}
