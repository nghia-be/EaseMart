<?php
namespace App\Imports;

use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;

class BrandImport implements ToModel
{
    public function model(array $row)
{
   
    $imageFileName = $row[1]; 
    $sourceImagePath = public_path('images/Brand/' . $imageFileName); 
    $storageFileName = Str::random(10) . '_' . $imageFileName; // Tạo tên mới cho ảnh
    $storagePath = 'upload/' . $storageFileName; // Đường dẫn lưu trong storage

    // Kiểm tra xem file có tồn tại không
    if (file_exists($sourceImagePath)) {
        // Đọc nội dung file
        $fileContent = file_get_contents($sourceImagePath);
        
        // Lưu file vào storage
        if (Storage::disk('public')->put($storagePath, $fileContent)) {
            // Đường dẫn để lưu vào DB theo định dạng mong muốn
            $imagePathForDb = '/storage/' . $storagePath; // Đường dẫn lưu vào DB
            $slug = Str::slug($row[0]);
            return new Brand([
                'name' => $row[0],           // Cột đầu tiên trong Excel
                'image' => $imagePathForDb,   // Đường dẫn lưu trong storage
                'slug' => $slug,
                'categories_id' => $row[2],   // Cột thứ ba trong Excel
                'status' => $row[3] ?? 1,     // Trạng thái mặc định nếu không có
            ]);
        }
    }

    return new Brand([
        
        'name' => $row[0],           // Cột đầu tiên trong Excel
        'image' => null,
        'slug' => null,              // Không có ảnh
        'categories_id' => $row[2],   // Cột thứ ba trong Excel
        'status' => $row[3] ?? 1,     // Trạng thái mặc định nếu không có
    ]);
}

}
