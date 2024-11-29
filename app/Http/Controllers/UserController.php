<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use App\Models\UserImage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = UserModel::all();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // Pastikan tabel 'users' benar
            'password' => 'required|string|min:6',
            'gender' => 'required|in:Laki-laki,Perempuan', // Validasi gender
            'weight' => 'required|numeric',
            'height' => 'required|numeric',
            'age' => 'nullable|integer',
            'ageRange' => 'nullable|string|max:20',
        ]);

        // Membuat instance baru dari UserModel
        $data = new UserModel();
        $data->username = $request->username;
        $data->email = $request->email;
        $data->password = Hash::make($request->password); // Meng-hash password sebelum disimpan
        $data->gender = $request->gender;
        $data->weight = $request->weight;
        $data->height = $request->height;
        $data->age = $request->age;
        $data->ageRange = $request->ageRange;
        $data->save(); // Menyimpan data ke database

        // Mengembalikan respons JSON
        return response()->json([
            'message' => 'Data created successfully',
            'data' => [
                'user_id' => $data->user_id, // Add user_id to the response
                'username' => $data->username,
                'email' => $data->email,
                'gender' => $data->gender,
                'weight' => $data->weight,
                'height' => $data->height,
                'age' => $data->age,
                'ageRange' => $data->ageRange,
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = UserModel::find($id);

        if (!$data) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    // Update the specified resource in storage.
    public function __construct()
    {
        $this->middleware('auth:api'); // Menambahkan middleware untuk memastikan user terautentikasi
    }

    public function update(Request $request, $id)
    {
        // Verifikasi jika user yang mengakses adalah user yang sama
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->id != $id) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Validasi data yang diterima
        $request->validate([
            'username' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:users,email,' . $user->id,
            'height' => 'sometimes|required|numeric',
            'weight' => 'sometimes|required|numeric',
            'gender' => 'sometimes|required|string',
        ]);

        // Update field yang diubah
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
            'message' => 'Data updated successfully',
            'data' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = UserModel::find($id);

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $user->delete();

            return response()->json(['message' => 'User deleted successfully'], 200);
        } catch (\Exception $e) {
            // Log error (opsional, jika Anda menggunakan logging)
            \Log::error('Error deleting user: ' . $e->getMessage());

            return response()->json(['message' => 'An error occurred while deleting the user'], 500);
        }
    }


    public function submitAge(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,user_id',
            'age' => 'required|integer|min:0',
        ]);

        $userImage = UserImage::where('user_id', $request->user_id)->first();
        if ($userImage) {
            $userImage->age = $request->age;
            $userImage->save();

            return response()->json(['message' => 'Usia berhasil disimpan'], 200);
        } else {
            return response()->json(['error' => 'User tidak ditemukan'], 404);
        }
    }

    public function currentUser(Request $request)
{
    $user = $request->user(); // Mendapatkan data pengguna dari token
    return response()->json([
        'status' => 'success',
        'data' => $user, // Data lengkap pengguna
    ]);
}


    private function determineAgeRange($age)
    {
        if ($age < 18) return 'Under 18';
        if ($age >= 18 && $age <= 30) return '18-30';
        if ($age >= 31 && $age <= 50) return '31-50';
        return 'Above 50';
    }



}
