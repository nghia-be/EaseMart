<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Schema\Builder;
use Spatie\Permission\Traits\HasRoles;

class ProductImage extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    protected $table = 'product_image';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'product_id',
        'image_path',  
        'alt_text',
        'is_primakey',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    // protected $hidden = [
    //     'password',
    //     'remember_token',
    // ];
    
        
    
    
    protected $guard_name = 'web';

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('H:i:s d-m-Y');
    }

}
