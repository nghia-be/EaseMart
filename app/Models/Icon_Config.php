<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Str;

class Icon_Config extends MY_Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'icon_configs';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['uuid', 'productConfigId', 'titleVi', 'titleEn', 'name', 'type', 'iconsPerRow', 'rowOnPage', 'arrayId', 'isDeleted', 'deleted_at'];

    protected static function boot() {
        parent::boot();

        static::creating(function($model) {
            if(empty($model->uuid)) {
                $model->uuid = Str::uuid();
            }
        });
    } 

    public function user() {
        return $this->belongsTo(User::class, 'id');
    }
}
