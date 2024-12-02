<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutUser extends Model
{
    use HasFactory;
    protected $table = 'workouts_user';
    protected $primaryKey = 'workouts_user_id';
    protected $fillable = ['user_id', 'workouts_id', 'workouts_details_id', 'completed'];

    //Relasi ke workout
    public function workout()
    {
        return $this->belongsTo(Workout::class, 'workouts_id');
    }
    
    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    // Relasi ke workouts_detail
    public function workoutDetail()
    {
        return $this->belongsTo(WorkoutDetail::class, 'workouts_details_id');
    }
}
