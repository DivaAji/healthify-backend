<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();

        $age = $user->age; 
        $height = $user->height; 
        $weight = $user->weight; 

        // Calculate BMI
        $bmi = $this->calculateBmi($height, $weight);

        // Return the user profile data with age range and BMI
        return response()->json([
            'user_id' => $user->user_id,
            'username' => $user->username,
            'email' => $user->email,
            'gender' => $user->gender,
            'height' => $user->height,
            'weight' => $user->weight,
            'ageRange' => $user->ageRange,
            'bmi' => $bmi,
            'profile_picture' => $user->profile_picture, // Assuming the user has a profile picture
        ]);
    }

    public function update(Request $request)
    {
        // Mendapatkan pengguna yang sudah terautentikasi
        $user = $request->user();  // You already have the authenticated user

        // Validasi data yang diterima
        $validated = $request->validate([
            'username' => 'sometimes|required|string|max:255|unique:users,username,' . $user->user_id . ',user_id',  
            'email' => 'sometimes|required|email|max:255|unique:users,email,' . $user->user_id . ',user_id', 
            'height' => 'sometimes|required|numeric',
            'weight' => 'sometimes|required|numeric',
            'gender' => 'sometimes|required|string',
        ]);

        // Update data pengguna
        if ($request->has('username')) {
            $user->username = $request->username;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('height')) {
            $user->height = $request->height;
        }
        if ($request->has('weight')) {
            $user->weight = $request->weight;
        }
        if ($request->has('gender')) {
            $user->gender = $request->gender;
        }

        // Simpan perubahan
        $user->save();

        // Kembalikan respons sukses
        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => $user
        ]);
    }



    // Method to calculate BMI (Weight in kg, Height in cm)
    private function calculateBmi($height, $weight)
    {
        // Convert height from cm to meters
        $heightInMeters = $height / 100;
        // Calculate BMI
        return round($weight / ($heightInMeters * $heightInMeters), 2);
    }
}

