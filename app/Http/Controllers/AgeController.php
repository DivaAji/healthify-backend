<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel; // Make sure to import your User model

class AgeController extends Controller
{
    /**
     * Update the specified user's age.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateAge(Request $request)
    {
        // Validate the input
        $request->validate([
            'user_id' => 'required|integer|exists:users,user_id', // Ensure user exists
            'ageRange' => 'required|string|in:Belum Remaja,Remaja,Dewasa,Lansia', // Validate age range
        ]);

        // Find the user by their user_id
        $user = UserModel::find($request->user_id);

        // Update the user's age range
        $user->age_range = $request->age_range;

        // Save the user with the updated age range
        $user->save();

        // Return success response
        return response()->json([
            'message' => 'Age range updated successfully.',
            'data' => $user
        ], 200);
    }
}
