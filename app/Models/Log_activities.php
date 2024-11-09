<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log_activities extends MY_Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'log_activities';
    protected $primaryKey = 'id';
    protected $fillable = [
        'param', 'url', 'method', 'ip', 'agent', 'user_id'
    ];
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function clearLog($dateBefore){
        return Log_activities::whereDate('created_at','<=',now()->subDays($dateBefore))->delete();
    }
}
