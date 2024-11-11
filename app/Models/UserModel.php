<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    use HasFactory;
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
    ];
    // Relasi User ke UserImage
    public function images()
    {
        return $this->hasMany(UserImage::class, 'user_id', 'user_id');
    }
}
