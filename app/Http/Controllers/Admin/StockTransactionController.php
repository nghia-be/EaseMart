<?php

namespace App\Http\Controllers\Admin;

use App\Models\Stock;
use App\Models\Units;
use App\Models\Products;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Support\Str;
use App\Imports\StockImport;
use App\Models\ProductUnits;

use Illuminate\Http\Request;
use App\Http\Traits\DataTrait;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\StockTransactionDetail;
use App\Http\Controllers\BaseController;
use App\DataTables\Admin\StockTransactionDataTable;

class StockTransactionController extends BaseController
{
    use DataTrait;
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Danh sách giao dịch';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StockTransactionDataTable $dataTable, Request $request)
    {   
        return $dataTable->render('stock-transaction.index');
    }

    public function storeImport(Request $request)
{
    $request->validate([
        'warehouse_code' => 'required',
        'supplier_code' => 'required',
        'note' => 'nullable|max:255',
        'products' => 'required|array',
        'products.*.id' => 'required|exists:products,id',
        'products.*.unit_code' => 'required',
        'products.*.quantity' => 'required|integer|min:1'
    ]);

    // Tạo mã giao dịch ngẫu nhiên với tiền tố 'IM'
    $transaction_code = 'IM' . strtoupper(Str::random(8));

    // Tạo bản ghi StockTransaction chính
    $stockTransaction = StockTransaction::create([
        'transaction_code' => $transaction_code,
        'transaction_type' => 1,
        'warehouse_code' => $request->warehouse_code,
        'supplier_code' => $request->supplier_code,
        'note' => $request->note,
        'created_by' => auth()->id(),
    ]);

    foreach ($request->products as $product) {
        // Lấy thông tin `unit_code` hiện tại của sản phẩm
        $currentUnit = DB::table('product_units')
            ->where('product_id', $product['id'])
            ->where('unit_code', $product['unit_code'])
            ->first();
        if (!$currentUnit) {
            return response()->json([
                'success' => 'fail',
                'message' => 'Đơn vị không hợp lệ cho sản phẩm ' . $product['id']
            ]);
        }
    
        // Kiểm tra nếu sản phẩm chỉ có 1 `unit_code`
        $unitCount = DB::table('product_units')
            ->where('product_id', $product['id'])
            ->count();
    
        if ($unitCount === 1) {
            // Nếu chỉ có 1 `unit_code`, sử dụng `quantity` như là số lượng cần lưu mà không cần quy đổi
            $quantity = $product['quantity'];
            $lowestUnit = $currentUnit; // Đặt lowestUnit là currentUnit vì chỉ có 1 unit
        } else {
            // Tìm `unit_code` có `level` nhỏ nhất cho sản phẩm này
            $lowestUnit = DB::table('product_units')
                ->where('product_id', $product['id'])
                ->orderBy('level', 'desc') // Lấy cấp thấp nhất (highest level number)
                ->first();
    
            if (!$lowestUnit) {
                return response()->json([
                    'success' => 'fail',
                    'message' => 'Không tìm thấy đơn vị thấp nhất cho sản phẩm ' . $product['id']
                ]);
            }
    
            // Quy đổi số lượng nếu `currentUnit` không phải là cấp thấp nhất
            $quantity = $product['quantity'];
            if ($currentUnit->level < $lowestUnit->level) {
                $quantity *= $currentUnit->exchangrate / $lowestUnit->exchangrate;
            }
        }
   
        StockTransactionDetail::create([
            'product_transaction_id' => $stockTransaction->id, // Giả định $stockTransaction đã được định nghĩa trước đó
            'product_id' => $product['id'],
            'unit_code' => $currentUnit->unit_code,
            'quantity' => $request->quantity,
        ]);
        // Cập nhật hoặc tạo mới bản ghi trong bảng Stock với `product_unit_id` của cấp thấp nhất
        Stock::updateOrCreate(
            [
                
                'product_id' => $product['id'],
                'warehouse_code' => $request->warehouse_code,
                'unit_code' => $lowestUnit->unit_code

            ],
            [
                'quantity' => DB::raw("quantity + $quantity"),
            ]
        );
    }

    // Ghi lại nhật ký giao dịch (nếu cần)
    $this->addToLog($request);

    return redirect()->back()->with([
        'success' => 'success', 
        'message' => 'Cập nhật thành công!'
    ]);
}

public function storeExport(Request $request)
{
    $request->validate([
        'warehouse_code' => 'required',
        'products' => 'required|array',
        'products.*.id' => 'required|exists:products,id',
        'products.*.unit_code' => 'required',
        'products.*.quantity' => 'required|integer|min:1'
    ]);

    // Tạo mã giao dịch ngẫu nhiên với tiền tố 'EX'
    $transaction_code = 'EX' . strtoupper(Str::random(8));

    // Tạo bản ghi StockTransaction chính
    $stockTransaction = StockTransaction::create([
        'transaction_code' => $transaction_code,
        'transaction_type' => 0, // Giả sử mã loại giao dịch xuất
        'warehouse_code' => $request->warehouse_code,
        'created_by' => auth()->id(),
    ]);

    foreach ($request->products as $product) {
        // Lấy thông tin về sản phẩm trong kho
        $stock = Stock::where('product_id', $product['id'])
            ->where('warehouse_code', $request->warehouse_code)
            ->first();

        // Kiểm tra xem sản phẩm có tồn tại trong kho không
        if (!$stock) {
            return response()->json([
                'success' => 'fail',
                'message' => 'Sản phẩm không tồn tại trong kho ' . $request->warehouse_code
            ]);
        }

        // Kiểm tra số lượng xuất có vượt quá tồn kho không
        if ($product['quantity'] > $stock->quantity) {
            return response()->json([
                'success' => 'fail',
                'message' => 'Số lượng xuất vượt quá tồn kho của sản phẩm ' . $product['id']
            ]);
        }

        // Tính toán số lượng cần xuất
        $currentUnit = DB::table('product_units')
            ->where('product_id', $product['id'])
            ->where('unit_code', $product['unit_code'])
            ->first();

        if (!$currentUnit) {
            return response()->json([
                'success' => 'fail',
                'message' => 'Đơn vị không hợp lệ cho sản phẩm ' . $product['id']
            ]);
        }

        // Tính toán số lượng để trừ đi trong kho
        $quantityToSubtract = $product['quantity'] * $currentUnit->exchangrate;

        // Cập nhật chi tiết giao dịch xuất vào StockTransactionDetail
        StockTransactionDetail::create([
            'product_transaction_id' => $stockTransaction->id,
            'product_id' => $product['id'],
            'unit_code' => $product['unit_code'],
            'quantity' => $product['quantity'], // Lưu lại số lượng xuất
        ]);

        // Cập nhật số lượng tồn kho sau khi xuất
        $stock->quantity -= $quantityToSubtract;
        $stock->save();
    }

    // Ghi lại nhật ký giao dịch (nếu cần)
    $this->addToLog($request);

    return redirect()->route('stock-transaction.index')->with(['success' => 'Xuất sản phẩm thành công!']);
}


    public function show(Request $request)
    {
        $module = StockTransactionDetail::where('product_transaction_id', $request->id)->get();
        return response(['data' => $module]);
    }
    public function import()
    {
    $list_product = $this->getAll(new Products)->where('status', 1)->load('primaryImage');
    $list_warehouse = $this->getAll(new Warehouse)->where('status', 1);
    $list_supplier = $this->getAll(new Supplier);
    $list_unit =  $this->getAll(new Units)->where('status', 1);
        $data = [
            'list_unit' => $list_unit,
            'list_product' => $list_product,
            'list_warehouse' => $list_warehouse,
            'list_supplier' => $list_supplier
        ];
        return view('stock-transaction.import',['data' => $data]); 
    }
    public function getProductUnits($productId)
{
    // Giả sử bạn có một model ProductUnit để lấy các đơn vị
    $productUnits = ProductUnits::where('product_id', $productId)->where('status', 1)->get();
    return response()->json($productUnits);
}

    public function export()
    {   
        $list_product = $this->getAll(new Products)->where('status', 1)->load('primaryImage');
        $list_warehouse = $this->getAll(new Warehouse)->where('status', 1);
        $list_unit =  $this->getAll(new Units)->where('status', 1);
            $data = [
                'list_unit' => $list_unit,
                'list_product' => $list_product,
                'list_warehouse' => $list_warehouse,
                
            ];
            return view('stock-transaction.export',['data' => $data]); 
    }
    
    

    public function update(Request $request, $id)
    {   
        $request->validate([
            'categories_name' => 'required|max:255',
        ]);
        $module = StockTransaction::findOrFail($request->id);
        $module->update($request->all());
        $this->addToLog($request);
        return response(['success' => 'success', 'message'=> 'Cập nhập thành công!']);
    }

    public function destroy(Request $request)
    {
        $module = StockTransaction::findOrFail($request->id);
        $module->delete();
        $this->addToLog(request());
        return response()->json(['message' => 'Xóa thành công!']);
    }
    public function importView()
    {
        return view('stock-transaction.import_view');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new StockImport, $request->file('file'));

        return redirect()->back()->with([
            'success' => true,
            'message' => 'Import thành công'
        ]);
    }
}
