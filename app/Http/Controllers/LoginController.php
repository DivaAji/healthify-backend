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
            // Jika cocok, buat token API
            $token = JWTAuth::fromUser($user); // Menggunakan user yang sudah diimplementasikan JWTSubject
            return response()->json(compact('token'));
        }
        
        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
