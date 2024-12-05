<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WorkoutCategoryUser;

class WorkoutUserController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'workouts_id' => 'required|integer|exists:workouts,workouts_id',
            'user_id' => 'required|integer|exists:users,user_id', // Validasi user_id
        ]);

        $user_id = $validated['user_id']; // Ambil user_id dari request

        // Tambahkan atau perbarui data di tabel workout_category_user
        WorkoutCategoryUser::updateOrCreate(
            [
                'user_id' => $user_id,
                'workouts_id' => $validated['workouts_id'],
            ],
            [
                'status' => 0, // Set status default ke 0 (Ongoing)
            ]
        );

        return response()->json([
            'message' => 'Workout category successfully added or updated!',
        ], 201);
    }
}
