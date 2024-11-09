<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Str;

class Icon extends MY_Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'icons';
    protected $fillable = ['uuid', 'productId','productNameVi', 'productNameEn', 'iconUrl', 'dataActionStaging', 'dataActionProduction', 'actionType', 'data', 'content', 'isNew', 'newBeginDay', 'newEndDay', 'isDisplay', 'displayBeginDay', 'displayEndDay', 'decriptionVi', 'decriptionEn', 'keywords', 'created_by', 'updated_by', 'deleted_at'];

    protected static function boot() {
        parent::boot();

        static::creating(function($model) {
            if(empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
        });
    } 

    public function user() {
        return $this->belongsTo(User::class, ['created_by', 'updated_by'], 'id');
    }

    public function category() {
        $relation =  $this->hasMany(Icon_Category::class, 'productId', 'arrayId');
    }

    public function config() {
        $relation =  $this->hasMany(Icon_Category::class, 'productId', 'arrayId');
    }
}
