<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserModel extends Model implements Authenticatable, JWTSubject
{
    use HasFactory;
    use AuthenticatableTrait;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'username',
        'email',
        'password',
        'gender',
        'height',
        'weight',
        'age',
        'ageRange',
    ];

    // Implementasi method yang diminta oleh JWTSubject
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Biasanya primary key, bisa sesuaikan jika berbeda
    }

    public function getJWTCustomClaims()
    {
        return []; // Klaim tambahan jika diperlukan
    }

    // Relasi User ke UserImage
    public function images()
    {
        return $this->hasMany(UserImage::class, 'user_id', 'user_id');
    }

    public function workoutsUser()
    {
        return $this->hasMany(WorkoutUser::class, 'user_id', 'user_id');
    }
}
