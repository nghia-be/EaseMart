<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Traits\Uuid;
use stdClass;

use Illuminate\Support\Str;

use App\Admin\UserController;
class Icon_Management extends MY_Model
{
    use HasFactory;
    use SoftDeletes;
    Use Uuid;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'icon_management';
    
    protected $fillable = ['uuid', 'productId', 'iconUrl', 'productNameEn', 'productNameVi', 'category', 'descriptionVi', 'descriptionEn', 'platform', 'dataActionStaging', 'dataActionProduction', 'is_filterable', 'show_position', 'deleted_at', 'updated_by', 'created_by', 'data'];
    
    protected static function boot() {
        parent::boot();

        static::creating(function($model) {
            if(empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
        });
    } 
    
    public function user() {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
