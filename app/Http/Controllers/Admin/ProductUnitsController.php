<?php

namespace App\Http\Controllers\Admin;

use App\Models\Units;
use App\Models\Products;
use App\Models\ProductUnits;
use Illuminate\Http\Request;
use App\Http\Traits\DataTrait;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\BaseController;
use App\DataTables\Admin\ProductUnitsDataTable;

class ProductUnitsController extends BaseController
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
    public function index(ProductUnitsDataTable $dataTable, Request $request)
    {   
        $list_product = $this->getAll(new Products)->load('primaryImage');;
        $list_unit = $this->getAll(new Units);
        $data = [
            'list_product' => $list_product,
            'list_unit' => $list_unit
        ];
        return $dataTable->render('product-units.index', ['data' => $data]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_code' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);
        ProductUnits::create($request->all());
        $this->addToLog(request());
        return response(['success' => 'success', 'message'=> 'Thêm mới thành công!']);
    }

    public function show(Request $request)
    {
        $unit = ProductUnits::findOrFail($request->id);
        return response(['data' => $unit]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
            'price_sale' => 'numeric|min:0|lt:price',
        ]);
        $unit = ProductUnits::findOrFail($request->id);
        $unit->update($request->all());
        $this->addToLog($request);
        return response(['success' => 'success', 'message'=> 'Sửa thành công!']);
    }

    public function destroy(Request $request)
    {
        $unit = ProductUnits::findOrFail($request->id);
        $unit->delete();
        $this->addToLog(request());
        return response()->json(['message' => 'Xóa thành công!']);
    }
    public function changeStatus(Request $request)
    {
        $unit = ProductUnits::findOrFail($request->id);
        $unit->status == 0 ? $unit->status =1 : $unit->status = 0;
        $unit->save();
        $this->addToLog(request());
        return response()->json(['message' => 'Thay đổi thành công!']);
    }
}
