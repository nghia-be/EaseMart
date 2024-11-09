<?php

namespace App\Http\Controllers\Api;

use App\Models\Products;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Routing\Controller;

class ProductController extends Controller
{
    
    public function getAll()
    {
        // Sử dụng eager loading để lấy sản phẩm và hình ảnh chính liên quan
        $products = Products::with('primaryImage')->get();
        
        // Trả về dữ liệu dưới dạng JSON
        return response()->json($products);
    }

    public function getProductDetail($id)
{
    // Tìm sản phẩm với các quan hệ images và productUnits
    try {
        $product = Products::with(['images', 'productUnits', 'categories'])->find($id);
    
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    
        // Tăng lượt xem cho sản phẩm
        $product->increment('views');
    
        // Lấy sản phẩm liên quan ngẫu nhiên (cùng danh mục)
        $relatedProducts = Products::where('categories_id', $product->categories_id)
            ->where('id', '!=', $product->id) // Để loại bỏ sản phẩm hiện tại
            ->inRandomOrder() // Sắp xếp ngẫu nhiên
            ->limit(2) // Lấy 4 sản phẩm liên quan
            ->get();
        
        // Trả về dữ liệu sản phẩm, hình ảnh và đơn vị
        return response()->json([
            'product' => $product->only(['id', 'name', 'description', 'categories_id', 'brand_id', 'slug', 'status', 'views', 'created_at', 'updated_at']),
            'images' => $product->images->map(function($image) {
                return [
                    'image_path' => $image->image_path,
                    'alt_text' => $image->alt_text,
                    'is_primakey' => $image->is_primakey
                ];
            }),
            'product_units' => $product->productUnits->map(function($unit) {
                return [
                    'unit_code' => $unit->unit_code,
                    'price' => $unit->price,
                    'price_sale' => $unit->price_sale
                ];
            }),
            
            'related_products' => $relatedProducts->map(function($relatedProduct) {
                // Lấy thông tin về đơn vị sản phẩm (productUnits) của sản phẩm liên quan
                $relatedProductUnit = $relatedProduct->productUnits->first(); // Lấy sản phẩm đơn vị đầu tiên
                
                // Kiểm tra xem sản phẩm liên quan có hình ảnh không
                $relatedProductImage = $relatedProduct->images->first();
                $relatedProductImagePath = $relatedProductImage ? $relatedProductImage->image_path : null;

                return [
                    'id' => $relatedProduct->id,
                    'name' => $relatedProduct->name,
                    'slug' => $relatedProduct->slug,
                    'price' => $relatedProductUnit ? $relatedProductUnit->price : null,
                    'unit_code' => $relatedProductUnit ? $relatedProductUnit->unit_code : null,
                    'image' => $relatedProductImagePath, // Lấy hình ảnh đầu tiên của sản phẩm liên quan (nếu có)
                ];
            })
        ]);
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Đã xảy ra lỗi không mong muốn, vui lòng kiểm tra lại',
            'error' => $e->getMessage(),
        ], 500);
    }
}


    

}

