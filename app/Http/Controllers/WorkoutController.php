<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Models\Workout;
use App\Models\WorkoutDetail;
use App\Models\WorkoutUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WorkoutController extends Controller
{
    public function getCategoriesByAgeRange($id)
    {
        \Log::info("Request received for userId: " . $id);

        // Ambil user berdasarkan ID
        $user = UserModel::find($id);
        if (!$user) {
            \Log::error("User not found for userId: " . $id);
            return response()->json(['message' => 'User not found'], 404);
        }

        // Tentukan kategori berdasarkan ageRange
        $categories = [];
        switch ($user->ageRange) {
            case 'Remaja':
                $categories = ['Kelincahan', 'Kelenturan', 'Keseimbangan'];
                break;
            case 'Dewasa':
                $categories = ['Kelenturan', 'Kardio'];
                break;
            case 'Lansia':
                $categories = ['Keseimbangan', 'Relaksasi'];
                break;
            default:
                \Log::error("Invalid ageRange for userId {$id}: " . $user->ageRange);
                return response()->json(['message' => 'Age range not recognized'], 400);
        }

        // Ambil workout berdasarkan kategori dan group per kategori
        $workouts = Workout::whereIn('category', $categories)
            ->with('workoutsDetails')
            ->get()
            ->groupBy('category');

        if ($workouts->isEmpty()) {
            \Log::warning("No workouts found for categories: " . implode(', ', $categories));
            return response()->json(['message' => 'No workouts available'], 404);
        }

        return response()->json([
            'ageRange' => $user->ageRange,
            'workouts' => $workouts
        ]);
    }

    public function getWorkoutDetails(Request $request)
    {
        $category = $request->query('category');

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category parameter is required.',
            ], 400);
        }

        $workouts = WorkoutDetail::where('category', $category)->get();

        if ($workouts->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No workouts found for this category.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'workouts_details' => $workouts,
        ]);
    }

    public function startProgram(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer|exists:users,user_id',
                'workouts_id' => 'required|integer|exists:workouts,workouts_id',
                'day_number' => 'required|integer|min:1',
            ]);

            $userId = $request->user_id;
            $workoutsId = $request->workouts_id;
            $dayNumber = $request->day_number;

            // Ambil 2 Pemanasan
            $warmups = WorkoutDetail::where('workouts_id', $workoutsId)
                ->where('sub_category', 'Pemanasan')
                ->take(2)
                ->get();

            // Ambil 2 Pendinginan
            $cooldowns = WorkoutDetail::where('workouts_id', $workoutsId)
                ->where('sub_category', 'Pendinginan')
                ->take(2)
                ->get();

            // Ambil 5 Latihan Inti
            $coreExercises = WorkoutDetail::where('workouts_id', $workoutsId)
                ->where('sub_category', 'Latihan Inti')
                ->take(5)
                ->get();

            \Log::info("Warmups: " . $warmups->count());
            \Log::info("Cooldowns: " . $cooldowns->count());
            \Log::info("Core Exercises: " . $coreExercises->count());

            // Gabungkan semua latihan yang diambil
            $allExercises = $warmups->merge($cooldowns)->merge($coreExercises);

            // Simpan setiap latihan ke tabel workouts_user
            foreach ($allExercises as $exercise) {
                $workoutsUser = new WorkoutUser();
                $workoutsUser->user_id = $userId;
                $workoutsUser->workouts_id = $workoutsId;
                $workoutsUser->workouts_details_id = $exercise->workouts_details_id;
                $workoutsUser->day_number = $dayNumber;
                $workoutsUser->completed = 0; // Belum selesai
                $workoutsUser->save();
            }

            return response()->json(['message' => 'Program berhasil dimulai'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error starting program: ' . $e->getMessage()
            ], 500);
        }
    }
}
