<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserImage extends Model
{
    use HasFactory;

    protected $table = 'user_images'; // Table name

    // Mass assignable fields
    protected $fillable = ['user_id', 'path','ageRange'];

    // Relation with User (assuming User model is named User)
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}
