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
            'age' => 'nullable|integer|min:0', // Validate age if provided
            'image' => 'nullable|image', // Validate image if provided
        ]);

        // Find the user by their user_id
        $user = UserModel::find($request->user_id);

        if ($request->has('age')) {
            // If the age is provided manually, update the age
            $user->age = $request->age;
        } elseif ($request->has('image')) {
            // If an image is provided, handle image upload and age detection
            $image = $request->file('image');
            // Store the image (this will store the image in 'public/images' folder)
            $path = $image->store('images', 'public');

            // Call an external service for age detection (this is just a placeholder)
            // For example, you can integrate with a machine learning model or third-party service
            $detectedAge = $this->detectAgeFromImage($path);

            if ($detectedAge) {
                // Update the user's age with the detected value
                $user->age = $detectedAge;
            } else {
                return response()->json([
                    'message' => 'Age detection failed.',
                ], 400);
            }
        } else {
            return response()->json([
                'message' => 'Either age or image must be provided.',
            ], 400);
        }

        // Save the user with the updated age
        $user->save();

        // Return success response
        return response()->json([
            'message' => 'Age updated successfully.',
            'data' => $user
        ], 200);
    }
}
