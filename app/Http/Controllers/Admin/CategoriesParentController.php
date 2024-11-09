<?php

namespace App\Http\Controllers\Admin;

use App\Models\Categories;
use Illuminate\Http\Request;
use App\Http\Traits\DataTrait;
use App\Models\CategoriesParent;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\BaseController;
use App\DataTables\Admin\CategoriesParentDataTable;

class CategoriesParentController extends BaseController
{
    use DataTrait;
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Danh sách danh mục cha';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CategoriesParentDataTable $dataTable, Request $request)
    {   
        return $dataTable->render('categories-parent.index');
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'categories_parents_name' => 'required|unique:categories_parent|max:255',
            'image' => 'required',
        ]);
        CategoriesParent::create($request->all());
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
        $module = CategoriesParent::findOrFail($request->id);
        return response(['data' => $module]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'categories_parents_name' => 'required|max:255',
        ]);
        $module = CategoriesParent::findOrFail($request->id);
        $module->update($request->all());
        $this->addToLog($request);
        return response(['success' => 'success', 'message'=> 'Sửa thành công!']);
    }

    public function destroy(Request $request)
    {
        $data = CategoriesParent::findOrFail($request->id);

        if ($data->image && Storage::exists($data->image)) {
            Storage::delete($data->image);
        }

        $data->delete();
        $this->addToLog(request());
        return response()->json(['message' => 'Xóa thành công!']);
    }
    public function changeStatus(Request $request)
    {
        $module = CategoriesParent::findOrFail($request->id);
        $module->status == 0 ? $module->status =1 : $module->status = 0;
        $module->save();
        $this->addToLog(request());
        return response()->json(['message' => 'Thay đổi thành công!']);
    }
}
