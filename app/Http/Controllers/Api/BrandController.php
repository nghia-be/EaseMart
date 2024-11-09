<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use Illuminate\Routing\Controller;

class BrandController extends Controller
{
    
public function getBrand()
{
    return Brand::all();
}

}
