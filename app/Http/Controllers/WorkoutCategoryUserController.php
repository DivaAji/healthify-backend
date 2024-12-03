<?php

namespace App\Http\Controllers;

use App\Models\WorkoutCategoryUser;
use Illuminate\Http\Request;

class WorkoutCategoryUserController extends Controller
{
    public function checkCategoryStatus($userId, $workoutsId)
    {
        // Cek apakah data ada di tabel workout_category_user untuk userId dan workoutsId
        $categoryUser = WorkoutCategoryUser::where('user_id', $userId)
                                            ->where('workouts_id', $workoutsId)
                                            ->first();

        // Log hasil pengecekan
        \Log::info("UserId: $userId, WorkoutsId: $workoutsId, Exists: " . ($categoryUser ? 'Yes' : 'No'));

        // Jika kategori untuk user ditemukan, periksa statusnya
        if ($categoryUser) {
            // Jika statusnya numerik atau lainnya, pastikan dipetakan dengan benar
            $status = $categoryUser->status == 1 ? 'completed' : 'ongoing';
        } else {
            // Jika kategori tidak ada untuk user ini, berarti statusnya not_started
            $status = 'not_started';
        }

        return response()->json([
            'status' => $status,  // 'ongoing', 'completed', or 'not_started'
        ]);
    }
}
