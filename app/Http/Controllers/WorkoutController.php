<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Models\Workout;
use App\Models\WorkoutDetail;
use App\Models\WorkoutUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
                $categories = ['Kelincahan', 'Kelenturan','Keseimbangan'];
                break;
            case 'Dewasa':
                $categories = ['Kelenturan', 'Keseimbangan'];
                break;
            case 'Lansia':
                $categories = ['Kelenturan', 'Keseimbangan'];
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

            // Step 1: Get the highest day_number for this user and workout_id
            $latestDayNumber = WorkoutUser::where('user_id', $userId)
                ->where('workouts_id', $workoutsId)
                ->max('day_number'); // Get the maximum day_number

            // Step 2: Increment day_number by 1
            $nextDayNumber = $latestDayNumber ? $latestDayNumber + 1 : 1;

            // Step 3: Fetch exercises (warmups, core exercises, and cooldowns)
            $warmups = WorkoutDetail::where('workouts_id', $workoutsId)
                ->where('sub_category', 'Pemanasan')
                ->take(2)
                ->get();

            $cooldowns = WorkoutDetail::where('workouts_id', $workoutsId)
                ->where('sub_category', 'Pendinginan')
                ->take(2)
                ->get();

            $coreExercises = WorkoutDetail::where('workouts_id', $workoutsId)
                ->where('sub_category', 'Latihan Inti')
                ->inRandomOrder()
                ->take(5)
                ->get();

            \Log::info("Warmups: " . $warmups->count());
            \Log::info("Cooldowns: " . $cooldowns->count());
            \Log::info("Core Exercises: " . $coreExercises->count());

            // Combine all exercises
            $allExercises = $warmups->merge($coreExercises)->merge($cooldowns);

            // Step 4: Insert data into workouts_user table with the next day_number
            foreach ($allExercises as $exercise) {
                $workoutsUser = new WorkoutUser();
                $workoutsUser->user_id = $userId;
                $workoutsUser->workouts_id = $workoutsId;
                $workoutsUser->workouts_details_id = $exercise->workouts_details_id;
                $workoutsUser->day_number = $nextDayNumber; // Set incremented day_number
                $workoutsUser->completed = 0; // Mark as not completed
                $workoutsUser->save();
            }

            return response()->json(['message' => 'Program successfully started'], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error starting program: ' . $e->getMessage()
            ], 500);
        }
    }


    public function getWorkoutSteps($userId, $workoutsId, $dayNumber)
    {
        try {
            // Ambil langkah-langkah latihan dari tabel workouts_user berdasarkan user_id, workouts_id, dan day_number
            $workoutUserDetails = WorkoutUser::where('user_id', $userId)
                ->where('workouts_id', $workoutsId)
                ->where('day_number', $dayNumber)
                ->where('completed', 0) // Filter berdasarkan yang belum selesai
                ->get();

            // Ambil detail latihan berdasarkan workouts_details_id yang ada di tabel workouts_user
            $workoutDetails = WorkoutDetail::whereIn('workouts_details_id', $workoutUserDetails->pluck('workouts_details_id'))
                ->get();

            // Kembalikan data langkah-langkah latihan
            return response()->json($workoutDetails);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error fetching workout steps: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateWorkoutUserProgress(Request $request)
    {
        // Menyimpan status progres latihan
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'workouts_id' => 'required|integer',
            'workouts_details_id' => 'required|integer',
            'completed' => 'required|boolean',
            'day_number' => 'required|integer',
        ]);

        $workoutUser = WorkoutUser::updateOrCreate(
            [
                'user_id' => $validated['user_id'],
                'workouts_id' => $validated['workouts_id'],
                'workouts_details_id' => $validated['workouts_details_id'],
                'day_number' => $validated['day_number'],
            ],
            ['completed' => $validated['completed']]
        );

        return response()->json($workoutUser);
    }

    public function getMaxDayNumber($userId, $workoutsId)
    {

        // Mengambil nilai terbesar day_number untuk user_id dan workouts_id tertentu
        $maxDayNumber = WorkoutUser::where('user_id', $userId)
                                    ->where('workouts_id', $workoutsId)
                                    ->max('day_number');
        
        // Kembalikan response
        return response()->json(['max_day_number' => $maxDayNumber ?? 1]);
    }

    public function checkUserWorkoutStatus(Request $request)
    {
        $userId = $request->input('user_id');
        $workoutsId = $request->input('workouts_id');
        $today = now()->toDateString();

        $exists = DB::table('workouts_user')
            ->where('user_id', $userId)
            ->where('workouts_id', $workoutsId)
            ->whereDate('updated_at', $today)
            ->exists();

        return response()->json(['exists' => $exists]);
    }

    public function getWorkoutHistory($userId, $date)
    {
        Log::info("User ID: $userId, Date: $date");

        try {
            // Ambil data dari workouts_user dengan tanggal dan status completed = 1
            $workouts = DB::table('workouts_user')
                ->join('workouts', 'workouts_user.workouts_id', '=', 'workouts.workouts_id') // Join with workouts table to get category
                ->where('workouts_user.user_id', $userId)
                ->whereDate('workouts_user.updated_at', $date)
                ->where('workouts_user.completed', 1)
                ->select('workouts_user.workouts_details_id', 'workouts_user.updated_at', 'workouts.category', 'workouts.workouts_id') // Select workouts_id (category) and other relevant fields
                ->get();

            // Ambil detail workouts dari workouts_detail berdasarkan workouts_details_id
            $workoutsDetails = DB::table('workouts_detail')
                ->whereIn('workouts_details_id', $workouts->pluck('workouts_details_id'))
                ->get();

            // Gabungkan data workouts_user, workouts, dan workouts_details
            $result = $workouts->map(function ($workout) use ($workoutsDetails) {
                // Get the workout detail based on workouts_details_id
                $detail = $workoutsDetails->firstWhere('workouts_details_id', $workout->workouts_details_id);
                return [
                    'name' => $detail->name ?? null,
                    'sub_category' => $detail->sub_category ?? null,
                    'description' => $detail->description ?? null,
                    'duration' => $detail->duration ?? null,
                    'category' => $workout->category ?? null, // The category from workouts table
                    'workouts_id' => $workout->workouts_id ?? null, // Add workouts_id to the result
                    'date' => $workout->updated_at,
                ];
            });

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching workout history: ' . $e->getMessage()], 500);
        }
    }


}
