<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use App\Models\Products;
use App\Models\Categories;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Http\Traits\DataTrait;
use Illuminate\Support\Facades\Storage;
use App\Imports\ProductsImport;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\BaseController;
use App\DataTables\Admin\ProductsDataTable;

class ProductsController extends BaseController
{
    use DataTrait;
    public $model;
    public function __construct()
    {
        parent::__construct();
        $this->title = 'Danh sach sản phẩm';
        $this->model = new Products();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductsDataTable $dataTable, Request $request)
    {
        $list_categories = $this->getAll(new Categories);
        $list_brand = $this->getAll(new Brand);
        
        
        $data = [
            'list_categories' => $list_categories,
            'list_brand' => $list_brand
        ];
        return $dataTable->render('products.index',['data' => $data]);
    }

    public function create(ProductsDataTable $dataTable)
    {
        $list_categories = $this->getAll(new Categories);
        $list_brand = $this->getAll(new Brand);
        $data = [
            'list_categories' => $list_categories,
            'list_brand' => $list_brand
        ];
        return $dataTable->render('products.create',['data' => $data]);
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|max:255', // Tên sản phẩm là bắt buộc và không quá 255 ký tự
            'path_1.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Validate hình ảnh
            'description' => 'nullable|string', // Mô tả là tùy chọn
            'categories_id' => 'required|exists:categories,id', // Danh mục là bắt buộc và phải tồn tại trong bảng categories
            'brand_id' => 'required|exists:brand,id', // Thương hiệu là bắt buộc và phải tồn tại trong bảng brands
        ]);
        $product = Products::create($request->all());
        if ($request->hasFile('path_1')) {
            
            foreach ($request->file('path_1') as $index => $image) {
                // Hash tên file
                $filename = hash('sha256', time() . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
    
                // Lưu hình ảnh vào thư mục
                $path = $image->storeAs('upload/products', $filename, 'public');
                $dbPath = '/storage/'.$path; // Lưu đường dẫn hình ảnh
    
                // Tạo bản ghi trong bảng product_images
               ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $dbPath,
                    'alt_text' => $request->input('alt_text.' . $index, ''), // Lấy alt_text nếu có, mặc định là ''
                    'is_primakey' => $index === 0 ? 1 : 0, // Đánh dấu hình ảnh đầu tiên là hình ảnh chính
                ]);
            }
        }
        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được thêm thành công!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    public function importView()
    {
        return view('products.import_view');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new ProductsImport(), $request->file('file'));

        return redirect()->back()->with([
            'success' => true,
            'message' => 'Thêm sản phẩm thành công!'
        ]);
    }

    public function edit($id)
    {
        $list_categories = $this->getAll(new Categories);
        $list_brand = $this->getAll(new Brand);
        $product = Products::find($id);
        $data = [
            'list_categories' => $list_categories,
            'list_brand' => $list_brand
        ];
        
        return view('products.edit')->with(['products'=>$product,'data'=>$data]);
    }

    

    public function update(Request $request, $id)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:255',
            'categories_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brand,id',
            'description' => 'nullable|string',
            'path_1.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Định dạng ảnh
        ]);
    
        // Tìm sản phẩm
        $product = Products::findOrFail($id);
    
        // Cập nhật thông tin sản phẩm
        $product->name = $request->input('name');
        $product->categories_id = $request->input('categories_id');
        $product->brand_id = $request->input('brand_id');
        $product->description = $request->input('description');
        $product->status = $request->has('status') ? 1 : 0; // Cập nhật trạng thái
        $product->save();
    
        // Xử lý hình ảnh
        if ($request->hasFile('path_1')) {
            
            foreach ($request->file('path_1') as $image) {
                // Tạo tên tệp duy nhất
                $filename = hash('sha256', time() . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
        
                // Lưu hình ảnh vào thư mục
                $path = $image->storeAs('upload/products', $filename, 'public');
                $dbPath = '/storage/' . $path; // Đường dẫn lưu trong cơ sở dữ liệu
                
                // Tạo bản ghi hình ảnh mới
                $newImage = ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $dbPath,
                    'is_primakey' => 0,
                ]);
                
                if (!$newImage) {
                    // Xử lý lỗi nếu không tạo được bản ghi
                    return back()->withErrors(['error' => 'Không thể thêm hình ảnh mới.']);
                }
                
            }
        }
   
        // Xóa các hình ảnh được đánh dấu xóa từ input
        if ($request->input('deleted_images')) {
            
            $deletedImageIds = explode(',', $request->input('deleted_images'));
            foreach ($deletedImageIds as $deletedImageId) {
                $imageToDelete = ProductImage::find($deletedImageId);
                if ($imageToDelete) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $imageToDelete->image_path));
                    $imageToDelete->delete();
                }
            }
        }
    
        // Cập nhật các hình ảnh đã chọn
        if ($request->input('img_path_1_names')) {
            $imageIds = explode(',', $request->input('img_path_1_names'));
    
            // Cập nhật hình ảnh chính nếu có
            if ($request->input('primary_image_id')) {
                $primaryImageId = $request->input('primary_image_id');
    
                // Cập nhật trạng thái hình ảnh chính
                ProductImage::where('product_id', $product->id)->update(['is_primakey' => false]); // Bỏ chọn tất cả
                ProductImage::where('id', $primaryImageId)->update(['is_primakey' => true]); // Đánh dấu hình ảnh chính
            }
    
            // Xóa các hình ảnh không được chọn
            $productImages = ProductImage::where('product_id', $product->id)->pluck('id')->toArray();
            $imagesToDelete = array_diff($productImages, $imageIds);
            ProductImage::destroy($imagesToDelete);
        }
    
        return redirect()->route('products.index')->with(['success'=>'Cập nhật sản phẩm thành công!']);
    }
    


    public function login(Request $request)
    {
        auth()->loginUsingId($request->id);
        return redirect()->intended('/');
    }

   public function destroy(Request $request)
   {
       $this->deleteById($this->model, $request->id);
       $this->addToLog(request());
       return response()->json(['message' => 'Xóa thành công!']);
   }
}
