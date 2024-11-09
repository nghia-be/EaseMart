<?php

namespace App\Http\Controllers\Admin;

use App\Models\Categories;
use Illuminate\Http\Request;
use App\Http\Traits\DataTrait;
use App\Models\CategoriesParent;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\BaseController;
use Spatie\Permission\Models\Permission;
use App\DataTables\Admin\CategoriesDabaTable;

class CategoriesController extends BaseController
{
    use DataTrait;
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Danh sách danh mục';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CategoriesDabaTable $dataTable, Request $request)
    {
        $list_icon = explode(",", file_get_contents(public_path('fontawsome.txt')));
        $list_categories_parent = $this->getAll(new CategoriesParent);
        
        $data = [
            'list_icon' => $list_icon,
            'list_categories_parent' => $list_categories_parent
        ];
        
        return $dataTable->render('categories.index', ['data' => $data]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'categories_name' => 'required|unique:categories|max:255', 
        ]);
        Categories::create($request->all());
        $this->addToLog(request());
        return response(['success' => 'success', 'message'=> 'Thêm danh mục thành công!']);
    }

    public function show(Request $request)
    {
        $module = Categories::findOrFail($request->id);
        return response(['data' => $module]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'categories_name' => 'required|max:255',
        ]);
        $module = Categories::findOrFail($request->id);
        $module->update($request->all());
        $this->addToLog($request);
        return response(['success' => 'success', 'message'=> 'Cập nhập thành công!']);
    }

    public function destroy(Request $request)
    {
        $data = Categories::findOrFail($request->id);

        if ($data->image && Storage::exists($data->image)) {
            Storage::delete($data->image);
        }

        $data->delete();
        $this->addToLog(request());
        return response()->json(['message' => 'Xóa thành công!']);
    }
}
