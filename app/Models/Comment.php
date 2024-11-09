<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    protected $table = 'comments';
    protected $primaryKey = 'id';
    protected $fillable = ['id','product_id','customer_id','rating','content'];
    
    public function customer()
{
    return $this->belongsTo(Customers::class);
}
public function product()
{
    return $this->belongsTo(Products::class,'product_id');
}
protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
    
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}
