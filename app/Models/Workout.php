<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    use HasFactory;
    protected $table = 'workouts';
    protected $primaryKey = 'workouts_id';
    protected $fillable = ['category'];

    // Relasi ke workouts_detail
    public function workoutsDetails()
    {
        return $this->hasMany(WorkoutDetail::class, 'workouts_id', 'workouts_id');
    }
}
