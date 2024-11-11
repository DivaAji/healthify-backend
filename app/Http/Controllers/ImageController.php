<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\UserImage;  // Make sure to import your UserImage model

class ImageController extends Controller
{
    /**
     * Fungsi untuk mengupload gambar
     */
    public function uploadImage(Request $request)
    {
        // Validasi file gambar
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi gambar
            'user_id' => 'required|exists:users,user_id',
            'age' =>'nullable|integer',  // Pastikan user_id ada di tabel users
        ]);

        // Proses upload gambar
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('user_images', 'public');  // Menyimpan gambar di folder public/storage/user_images

            // Simpan informasi gambar ke database
            $userImage = new UserImage();
            $userImage->user_id = $request->input('user_id');  // Menyimpan user_id yang diambil dari request
            $userImage->path = $path;  // Menyimpan path gambar
            $userImage->save();  // Simpan data ke database

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
