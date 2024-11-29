<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutDetail extends Model
{
    use HasFactory;
    protected $table = 'workouts_detail';
    protected $primaryKey = 'workouts_details_id';
    protected $fillable = ['workouts_id', 'name', 'sub_category', 'description', 'duration', 'video_link'];

    // Relasi ke workouts
    public function workout()
    {
        return $this->belongsTo(Workout::class, 'workouts_id');
    }

    // Relasi ke workouts_user
    public function workoutsUser()
    {
        return $this->hasMany(WorkoutUser::class, 'workouts_details_id', 'workouts_details_id');
    }
}
