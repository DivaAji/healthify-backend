<?php
namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Models\Workout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WorkoutController extends Controller
{
    public function getCategoriesByAgeRange($id)
    {
        \Log::info("Request received for userId: " . $id); // Log request yang diterima

        // Ambil user berdasarkan ID
        $user = UserModel::find($id);
        if (!$user) {
            \Log::error("User not found for userId: " . $id); // Log error jika user tidak ditemukan
            return response()->json(['message' => 'User not found'], 404);
        }

        // Tentukan kategori berdasarkan ageRange
        $categories = [];
        switch ($user->ageRange) {
            case 'Remaja':
                $categories = ['Kelincahan', 'Kelenturan','Keseimbangan']; // Tambahkan kategori tambahan jika diperlukan
                break;
            case 'Dewasa':
                $categories = ['Kelenturan', 'Kardio']; // Tambahkan kategori tambahan
                break;
            case 'Lansia':
                $categories = ['Keseimbangan', 'Relaksasi']; // Tambahkan kategori tambahan
                break;
            default:
                \Log::error("Invalid ageRange for userId {$id}: " . $user->ageRange);
                return response()->json(['message' => 'Age range not recognized'], 400);
        }

        // Ambil workout berdasarkan kategori dan group per kategori
        $workouts = Workout::whereIn('category', $categories)
            ->with('workoutsDetails')  // Make sure 'workoutsDetails' is properly loaded
            ->get()
            ->groupBy('category'); // Group workouts by category

        if ($workouts->isEmpty()) {
            \Log::warning("No workouts found for categories: " . implode(', ', $categories));
            return response()->json(['message' => 'No workouts available'], 404);
        }

        // Return workouts grouped by category
        return response()->json([
            'ageRange' => $user->ageRange, 
            'workouts' => $workouts
        ]);
    }
}
