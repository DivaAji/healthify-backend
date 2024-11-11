<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserImage;

class ImageController extends Controller
{
    public function uploadImage(Request $request)
{
    // Logging request data for debugging
    \Log::info('Upload request:', $request->all());

    try {
        // Validate file input
        $request->validate([
            'user_id' => 'required|exists:users,user_id', 
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:16384', 
            'age' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('user_images', 'public'); 

            $userImage = new UserImage();
            $userImage->user_id = $request->user_id;
            $userImage->path = $path;
            $userImage->age = $request->age; 
            $userImage->save();

            return response()->json([
                'message' => 'Image uploaded successfully',
                'path' => $path
            ], 200);
        }
        return response()->json(['message' => 'No image found'], 400);
    } catch (\Exception $e) {
        // Log exception for further investigation
        \Log::error('Image upload error: '.$e->getMessage());
        return response()->json(['message' => 'Upload failed'], 500);
    }
}

}
