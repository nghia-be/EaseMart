<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Categories;
use App\Imports\BrandImport;
use Illuminate\Http\Request;
use App\Http\Traits\DataTrait;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\DataTables\Admin\BrandDatatable;
use App\Http\Controllers\BaseController;
use Spatie\Permission\Models\Permission;


class BrandController extends BaseController
{
    use DataTrait;
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Danh sách thương hiệu';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BrandDatatable $dataTable, Request $request)
    {
        $list_brand = $this->getAll(new Brand);
        $list_categories = $this->getAll(new Categories);
        $data = [
            'list_brand' => $list_brand,
            'list_categories' => $list_categories
        ];
        return $dataTable->render('brand.index', ['data' => $data]);
    }
    public function getBrand(){
        return Brand::all();
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:brand|max:255',
            'categories_id' => 'required|exists:categories,id', 
        ]);
        Brand::create($request->all());
        $this->addToLog(request());
        return response(['success' => 'success', 'message'=> 'Thêm thương hiệu thành công!']);
    }

    public function show(Request $request)
    {
        $data = Brand::findOrFail($request->id);
        return response(['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
        ]);
        $data = Brand::findOrFail($request->id);
        $data->update($request->all());
        $this->addToLog($request);
        return response(['success' => 'success', 'message'=> 'Cập nhập thành công!']);
    }

    public function destroy(Request $request)
    {
        $data = Brand::findOrFail($request->id);

        if ($data->image && Storage::exists($data->image)) {
            Storage::delete($data->image);
        }

        $data->delete();
        $this->addToLog(request());
        return response()->json(['message' => 'Xóa thành công!']);
    }
    public function changeStatus(Request $request)
    {
        $module = Brand::findOrFail($request->id);
        $module->status == 0 ? $module->status =1 : $module->status = 0;
        $module->save();
        $this->addToLog(request());
        return response()->json(['message' => 'Thay đổi thành công!']);
    }

    public function importView()
    {
        return view('brand.import_view');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new BrandImport(), $request->file('file'));

        return redirect()->back()->with([
            'success' => 'success',
            'message' => 'Thêm thương hiệu thành công!'
        ]);
    }
}
