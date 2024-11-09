<?php

namespace App\Http\Controllers\Admin;

use App\Models\Stock;
use Illuminate\Http\Request;
use App\Http\Traits\DataTrait;
use Illuminate\Support\Facades\Storage;
use App\DataTables\Admin\StockDataTable;
use App\Http\Controllers\BaseController;

class StockController extends BaseController
{
    use DataTrait;
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Quản lý kho hàng';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StockDataTable $dataTable, Request $request)
    {   
        return $dataTable->render('stock.index');
    }

    public function store(Request $request)
    {
        
        // $request->validate([
        //     'categories_parents_name' => 'required|unique:categories_parent|max:255',
        //     'image' => 'required',
        // ]);
        Stock::create($request->all());
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
        $module = Stock::findOrFail($request->id);
        return response(['data' => $module]);
    }

    public function update(Request $request)
    {
        // $validated = $request->validate([
        //     'categories_parents_name' => 'required|max:255',
        // ]);
        $module = Stock::findOrFail($request->id);
        $module->update($request->all());
        $this->addToLog($request);
        return response(['success' => 'success', 'message'=> 'Sửa thành công!']);
    }

    public function destroy(Request $request)
    {
        $data = Stock::findOrFail($request->id);

        if ($data->image && Storage::exists($data->image)) {
            Storage::delete($data->image);
        }

        $data->delete();
        $this->addToLog(request());
        return response()->json(['message' => 'Xóa thành công!']);
    }
    public function changeStatus(Request $request)
    {
        $module = Stock::findOrFail($request->id);
        $module->status == 0 ? $module->status =1 : $module->status = 0;
        $module->save();
        $this->addToLog(request());
        return response()->json(['message' => 'Thay đổi thành công!']);
    }
}
