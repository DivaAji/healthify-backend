<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\UserImage;  // Model UserImage untuk tabel user_images

class ImageController extends Controller
{
    /**
     * Fungsi untuk mengupload gambar
     */
    public function uploadImage(Request $request)
    {
        // Validasi file gambar
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user_id' => 'required|exists:users,user_id'  // Pastikan user_id ada di tabel users
        ]);

        // Proses upload gambar
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('user_images', 'public');  // Menyimpan gambar di folder public/storage/user_images

            // Simpan informasi gambar ke database
            $userImage = new UserImage();
            $userImage->user_id = $request->input('user_id');
            $userImage->path = $path;
            $userImage->save();

            return response()->json([
                'message' => 'Gambar berhasil diupload',
                'path' => $path
            ], 200);
        }

        return response()->json([
            'message' => 'No image file found'
        ], 400);
    }
}
