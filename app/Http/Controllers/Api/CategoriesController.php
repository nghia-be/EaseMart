<?php

namespace App\Http\Controllers\Api;

use App\Models\Categories;
use App\Models\CategoriesParent;
use Illuminate\Routing\Controller;

class CategoriesController extends Controller
{
    
    public function show()
    {
        $categoriesParents = CategoriesParent::where('status', 1)->with('categories')->get();
        return response()->json($categoriesParents);
    }
    public function index()
    {
        // Giả sử bạn đã có dữ liệu trong cơ sở dữ liệu
        $categoriesParent = CategoriesParent::with('categories')->get();

        // Chuyển đổi dữ liệu theo định dạng yêu cầu
        $data = $categoriesParent->map(function ($parent) {
            return [
                'id' => $parent->id,
                'name' => $parent->categories_parents_name,
                'image' => $parent->image,
                'categories' => $parent->categories->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->categories_name,
                        'image' => $category->image,
                    ];
                }),
            ];
        });

        return response()->json(['categories_parent' => $data]);
    }

}
