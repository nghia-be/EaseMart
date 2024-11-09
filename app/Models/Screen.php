<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Screen extends MY_Model
{
    use HasFactory;
    protected $table = 'screen';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = ['screenId','screenName','typeLog','api_url','image','example_code','status'];

    public function createdBy(){
        return $this->hasOne(User::class,'id','created_by');
    }
}
