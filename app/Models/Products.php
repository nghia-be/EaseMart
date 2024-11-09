<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Schema\Builder;
use Spatie\Permission\Traits\HasRoles;

class Products extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    use SoftDeletes;
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'name',
        'description',
        'categories_id',
        'brand_id',
        'slug',
        'status' 
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    // protected $hidden = [
    //     'password',
    //     'remember_token',
    // ];

    public function getProductUnits($productId)
    {
        // Giả sử `ProductUnits` có quan hệ với `Product`
        $productUnits = ProductUnits::where('product_id', $productId)->where('status', 1)->get();
    
        return response()->json($productUnits);
    }
    

    public function primaryImagePath()
    {
        // Sử dụng hasOne để lấy ra bản ghi đầu tiên có is_primakey = 1 từ bảng product_image
        $image = $this->hasOne(ProductImage::class, 'product_id')
                      ->where('is_primakey', 1)
                      ->first();

        return $image ? $image->image_path : null;
    }
    public function images()
{
    return $this->hasMany(ProductImage::class, 'product_id'); // Lọc ra các hình ảnh không bị xóa (nếu bạn đang sử dụng soft delete)
}
public function stockTransactionDetails()
        {
            return $this->hasMany(StockTransactionDetail::class); // Quan hệ với bảng StockTransactionDetail
        }
public function stock()
{
    return $this->hasMany(Stock::class, 'product_id', 'id');
}
public function primaryImage()
{
    return $this->hasOne(ProductImage::class, 'product_id')
                ->where('is_primakey', 1);
}
    public function productUnits()
    {
        return $this->hasMany(ProductUnits::class,'product_id');
    }
    public function categories()
    {
        return $this->belongsTo(Categories::class, 'categories_id');
    }public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
    protected $guard_name = 'web';

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('H:i:s d-m-Y');
    }

}
