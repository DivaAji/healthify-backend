<?php

namespace App\Http\Controllers;

use App\Models\WorkoutDetail;
use Illuminate\Http\Request;

class WorkoutScreenController extends Controller
{
    public function getWorkoutDetails($workoutsId)
    {
        try {
            // Ambil 2 Pemanasan
            $warmups = WorkoutDetail::where('workouts_id', $workoutsId)
                ->where('sub_category', 'Pemanasan')
                ->take(2) // Mengambil 2 latihan Pemanasan
                ->get();

            // Ambil 2 Pendinginan
            $cooldowns = WorkoutDetail::where('workouts_id', $workoutsId)
                ->where('sub_category', 'Pendinginan')
                ->take(2) // Mengambil 2 latihan Pendinginan
                ->get();

            // Ambil 5 Latihan Inti
            $coreExercises = WorkoutDetail::where('workouts_id', $workoutsId)
                ->where('sub_category', 'Latihan Inti')
                ->take(5) // Mengambil 5 latihan Inti
                ->get();

            // Gabungkan semua latihan yang diambil
            $workoutDetails = $warmups->merge($cooldowns)->merge($coreExercises);

            // Kembalikan hasil dalam format JSON
            return response()->json([
                'workout_details' => $workoutDetails
            ]);
        } catch (\Exception $e) {
            // Menangani error jika terjadi
            return response()->json([
                'error' => 'Error fetching workout details: ' . $e->getMessage()
            ], 500);
        }
    }
}
