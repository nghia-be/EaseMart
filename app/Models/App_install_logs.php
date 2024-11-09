<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Support\Str;

class App_install_logs extends MY_Model
{
    use HasFactory;

    protected $table = 'log_report';
    protected $keyType = 'string';
    protected $fillable = ['report_type_id', 'date_report', 'source', 'data', 'created_at', 'updated_at'];

        /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted() {
        static::addGlobalScope('report_type_id', function (Builder $builder) {
            $builder->where('report_type_id', '=', 1);
        });
    }

    public function user() {
        return $this->belongsTo(User::class, 'id');
    }
}
