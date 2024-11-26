<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    use HasFactory;
    protected $primaryKey = 'workout_id';
    protected $fillable = [
        'name', 'category', 'description', 'duration',
    ];

    public function scopeByAgeCategory($query, $age)
    {
        if ($age >= 18 && $age <= 30) {
            return $query->where('category', 'Strength');
        } elseif ($age > 30 && $age <= 50) {
            return $query->where('category', 'Cardio');
        } elseif ($age > 50) {
            return $query->where('category', 'Core');
        }
        return $query->whereRaw('1 = 0'); // Tidak mengembalikan hasil jika usia < 18
    }
}

