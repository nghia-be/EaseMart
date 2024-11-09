<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use stdClass;

use Illuminate\Support\Str;

class Icon_Category extends MY_Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'icon_categories';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['uuid', 'productTitleId', 'productTitleNameVi', 'productTitleNameEn', 'arrayId', 'isDisplay', 'created_by', 'deleted_at', 'displayBeginDay', 'displayEndDay'];

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
