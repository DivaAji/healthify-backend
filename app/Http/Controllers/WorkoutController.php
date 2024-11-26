<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use Illuminate\Http\Request;

class WorkoutController extends Controller
{
    public function getWorkoutsByAge(Request $request)
    {
        $predictedAge = $request->age;

        if ($predictedAge < 18) {
            return response()->json(['message' => 'Anda belum memenuhi syarat usia minimal untuk menggunakan aplikasi ini.'], 403);
        }

        $category = match (true) {
            $predictedAge >= 18 && $predictedAge <= 30 => 'Strength',
            $predictedAge > 30 && $predictedAge <= 50 => 'Cardio',
            $predictedAge > 50 => 'Core',
            default => null,
        };

        $workouts = Workout::where('category', $category)->get();

        return response()->json(['workouts' => $workouts]);
    }
}
