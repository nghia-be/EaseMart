<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Schema\Builder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class Customers extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */ protected $table = 'customers';
        protected $primaryKey = 'id';
        protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'image',
        'status',
        'gender',
        'birth_date',
        'deleted_at',
        'updated_by',
        'created_by'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $guard_name = 'web';
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('H:i:s d-m-Y');
    }

}
