<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserImage extends Model
{
    use HasFactory;

    // Tentukan nama tabel jika tidak mengikuti konvensi
    protected $table = 'user_images';

    // Tentukan kolom yang bisa diisi (mass assignable)
    protected $fillable = ['user_id', 'path','age'];

    // Relasi UserImage ke User
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    public function uploadImage(Request $request)
    {
        // Validasi file gambar
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user_id' => 'required|exists:users,id',  // Validasi user_id yang ada di database
            'age' => 'nullable|integer',
        ]);

        // Proses upload gambar
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $path = $image->store('user_images', 'public');  // Menyimpan gambar di folder public/storage/user_images

            // Simpan informasi gambar ke database
            $userImage = new UserImage();
            $userImage->user_id = $request->input('user_id');
            $userImage->path = $path;
            $userImage->age = null;  // Set null for now
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
