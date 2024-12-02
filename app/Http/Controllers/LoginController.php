<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\UserModel;

class LoginController extends Controller
{
    public function login(Request $request)
{
    // Validasi input
    $credentials = $request->only('username', 'password');

    // Cek login
    $user = UserModel::where('username', $request->username)->first();
    
    if ($user && Hash::check($request->password, $user->password)) {
        // Jika user ditemukan dan password cocok, buat token
        $token = JWTAuth::fromUser($user);

        // Menggunakan compact untuk mengembalikan data dalam format array
        return response()->json(array_merge(compact('token'), ['user_id' => $user->user_id]));
    }

    // Jika login gagal, beri respons error
    return response()->json(['message' => 'Unauthorized'], 401);
}

}
