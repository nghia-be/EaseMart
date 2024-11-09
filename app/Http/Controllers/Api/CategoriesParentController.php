<?php

namespace App\Http\Controllers\Api;

use App\Models\CategoriesParent;
use Illuminate\Routing\Controller;

class CategoriesParentController extends Controller
{
    
public function getCategoryParents()
{
    $categoriesParent = CategoriesParent::where('status', 1)->get();
    return response()->json($categoriesParent);
}


}
