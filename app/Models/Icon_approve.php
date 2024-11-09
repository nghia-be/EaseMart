<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

use App\Admin\UserController;
// use App\Admin\SettingsController;
// use App\Models\Settings;

class Icon_approve extends MY_Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'icon_approves';
    protected $primaryKey = 'id';
    protected $fillable = ['product_type','product_id', 'approved_type', 'approved_status', 'approved_by', 'approved_at', 'created_at', 'updated_by', 'updated_at', 'deleted_at', 'requested_at', 'requested_by', 'checked_by', 'checked_at', 'api_logs'];
    protected $casts = [
        'value' => 'array'
    ];

    public function icon() {
        return $this->belongsTo(Icon::class, 'product_id', 'uuid')->withTrashed();
    }

    public function icon_category() {
        return $this->belongsTo(Icon_Category::class, 'product_id', 'uuid')->withTrashed();
    }

    public function icon_config() {
        return $this->belongsTo(Icon_Config::class, 'product_id', 'uuid')->withTrashed();
    }

    public function user_requested_by() {
        return $this->belongsTo(User::class, 'requested_by', 'id');
    }

    public function user_checked_by() {
        return $this->belongsTo(User::class, 'checked_by', 'id');
    }

    public function user_approved_by() {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function settings() {
        $relation =  $this->belongsTo(Settings::class);
        $relation->select('value->key as key');
        $relation->where('icon_approves.approved_status', 'settings.key');
        // dd($relation->toSql());
        return $relation;
    }
}
