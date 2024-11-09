<?php
namespace App\Imports;

use App\Models\Products;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsImport implements ToModel, WithHeadingRow
{
    public function headings(): array
    {
        return [
        // Cột 10
        ];
    }
    public function model(array $row)
    {
   
        // Tạo slug từ tên sản phẩm
        $slug = Str::slug($row[0]); // Giả sử cột tên sản phẩm là 'tên sản phẩm'

        // Tạo sản phẩm mới
        $product = Products::create([
            'name' => $row[0],
            'description' => $row[1],
            'categories_id' => $row[2],
            'brand_id' => $row[3],
            'slug' => $slug, 
            

        ]);

        // Xử lý hình ảnh
        $isFirstImage = true;
        for ($i = 7; $i <= 9; $i++) { // Giả sử bạn có tối đa 3 hình ảnh
            if (!empty($row[$i])) {
                $imageFileName = $row[$i]; 
                
                $sourceImagePath = public_path('images/Products/' . $imageFileName);  
                
                // Tạo tên mới cho ảnh
                $storageFileName = Str::random(10) . '_' . $imageFileName; 
                $storagePath = 'upload/' . $storageFileName; 
                
                // Di chuyển hình ảnh vào thư mục upload
                if (file_exists($sourceImagePath)) {
                    // Di chuyển ảnh vào thư mục lưu trữ
                    $fileContent = file_get_contents($sourceImagePath);
                    
                    if (Storage::disk('public')->put($storagePath, $fileContent)) {
                        // Đường dẫn để lưu vào DB theo định dạng mong muốn
                        $imagePathForDb = '/storage/' . $storagePath; // Đường dẫn lưu vào DB
                       ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $imagePathForDb,
                            'is_primakey' => $isFirstImage ? 1 : 0,
                        ]);
                        $isFirstImage = false;
                    }
                }
            }
        }

        return $product;
    }

}
