<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutCategoryUser extends Model
{
    use HasFactory;
    protected $table = 'workouts_category_user';
    protected $primaryKey = 'workouts_category_user_id';
    protected $fillable = ['user_id', 'workouts_id', 'status'];

    // Relasi ke workout
    public function workout()
    {
        return $this->belongsTo(Workout::class, 'workouts_id', 'workouts_id');
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}

