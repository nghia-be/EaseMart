<?php

namespace App\Http\Controllers\Api;

use App\Models\Stock;
use App\Models\Products;
use App\Models\CategoriesParent;
use Illuminate\Routing\Controller;

class StockTransactionController extends Controller
{
    
    public function getProductsByWarehouse($warehouse_code)
    {
        // Lấy danh sách sản phẩm có sẵn trong kho được chọn
        $products = Products::whereHas('stock', function ($query) use ($warehouse_code) {
            $query->where('warehouse_code', $warehouse_code);
        })->with('primaryImage')->get();
    
        // Trả về JSON danh sách sản phẩm
        return response()->json($products);
    }
}
