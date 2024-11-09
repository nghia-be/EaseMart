<?php

namespace App\Http\Controllers\Admin;

use App\Models\Customers;
use Illuminate\Http\Request;
use App\Http\Traits\DataTrait;
use App\Models\CategoriesParent;
use App\Http\Controllers\BaseController;
use App\DataTables\Admin\CategoriesParentDataTable;
use App\DataTables\Admin\CustomersDataTable;

class CustomersController extends BaseController
{
    use DataTrait;
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Danh sách khách hàng';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CustomersDataTable $dataTable, Request $request)
    {   
        return $dataTable->render('customers.index');
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required|unique:customers|max:255',
            'image' => 'required',
        ]);
        Customers::create($request->all());
        $this->addToLog(request());
        return response(['success' => 'success', 'message'=> 'Thêm mới thành công!']);
    }

//     public function edit($id)
// {
//     $categoriesparent = CategoriesParent::find($id);
//     return view('categories-parent.create')->with(['categoriesparent' => $categoriesparent]);
// }

    public function show(Request $request)
    {
        $module = Customers::findOrFail($request->id);
        return response(['data' => $module]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
        ]);
        $module = Customers::findOrFail($request->id);
        $module->update($request->all());
        $this->addToLog($request);
        return response(['success' => 'success', 'message'=> 'Sửa thành công!']);
    }

    public function destroy(Request $request)
    {
        $module = Customers::findOrFail($request->id);
        $module->delete();
        $this->addToLog(request());
        return response()->json(['message' => 'Xóa thành công!']);
    }
    public function changeStatus(Request $request)
    {
        $module = Customers::findOrFail($request->id);
        $module->status == 0 ? $module->status =1 : $module->status = 0;
        $module->save();
        $this->addToLog(request());
        return response()->json(['message' => 'Thay đổi thành công!']);
    }
}
