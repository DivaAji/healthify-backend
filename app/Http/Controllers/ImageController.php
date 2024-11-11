<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserImage;

class ImageController extends Controller
{
    public function uploadImage(Request $request)
    {
        // Validate file input
        $request->validate([
            'user_id' => 'required|exists:users,user_id', // Ensure the user exists
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate image
            'age' => 'nullable|integer',
        ]);

        // Store the image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('user_images', 'public');  // Store image in 'public/user_images'

            // Save the image path to the database in the 'user_images' table
            $userImage = new UserImage();
            $userImage->user_id = $request->user_id;
            $userImage->path = $path;
            $userImage->age = null;  // Optional: set a default age or calculate it later
            $userImage->save();

            return response()->json([
                'message' => 'Image uploaded successfully',
                'path' => $path
            ], 200);
        }

        return response()->json(['message' => 'No image found'], 400);
    }
}
