<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriesParent extends MY_Model
{
    use SoftDeletes;
    protected $table = 'categories_parent';
    protected $primaryKey = 'id';
    protected $fillable = ['id','categories_parents_name','image','status'];
    
    public function categories()
    {
        return $this->hasMany(Categories::class, 'categories_parent_id', 'id');
    }

    public function createdBy(){
        return $this->hasOne(User::class,'id','created_by');
    }
    public function updatedBy(){
        return $this->hasOne(User::class,'id','updated_by');
    }
}

