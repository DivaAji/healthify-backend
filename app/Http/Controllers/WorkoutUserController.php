<?php
namespace App\Http\Controllers;

use App\Models\WorkoutUser;
use App\Models\WorkoutCategoryUser;
use App\Models\WorkoutDetail;
use Illuminate\Http\Request;
use App\Models\Workout;
use App\Models\UserModel;

class WorkoutUserController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'workouts_id' => 'required|integer|exists:workouts,workouts_id',
            'completed' => 'required|boolean',
            'user_id' => 'required|integer|exists:users,user_id', // Validate user_id
        ]);

        $user_id = $validated['user_id']; // Get user_id from the request

        // Menyimpan status workout kategori
        WorkoutCategoryUser::updateOrCreate(
            [
                'user_id' => $user_id,
                'workouts_id' => $validated['workouts_id']
            ],
            ['status' => 0] // Status default 0 (Ongoing)
        );

        // Ambil semua workout details berdasarkan workouts_id yang diterima
        $workoutsDetails = \App\Models\WorkoutDetail::where('workouts_id', $validated['workouts_id'])->get();

        // Menyimpan data ke tabel workouts_user untuk setiap detail workout
        foreach ($workoutsDetails as $detail) {
            WorkoutUser::create([
                'user_id' => $user_id,
                'workouts_id' => $validated['workouts_id'],
                'workouts_details_id' => $detail->workouts_details_id,
                'completed' => $validated['completed'],
            ]);
        }

        return response()->json([
            'message' => 'Workout program selected successfully!',
        ], 201);
    }

    public function getWorkoutsByCategory($userId)
    {
        // Ambil data dari workouts_category_user untuk user_id
        $workoutsCategoryUser = WorkoutCategoryUser::where('user_id', $userId)->get();
        
        $workoutsWithStatus = $workoutsCategoryUser->map(function ($category) {
            $workoutId = $category->workouts_id;

            // Ambil data dari workouts_category_detail untuk mengecek status completed
            $categoryDetail = WorkoutDetail::where('workouts_id', $workoutId)->first();

            // Jika completed = 0, berarti kategori masih ongoing
            $category->isOngoing = $categoryDetail && $categoryDetail->completed == 0;

            return $category;
        });

        return response()->json($workoutsWithStatus);
    }

}
