<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

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
    public function update(Request $request, $id)
    {
        $data = UserModel::find($id);

        if (!$data) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $request->validate([
            'username' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'password' => 'sometimes|required|string|min:6',
            'gender' => 'sometimes|required|in:Laki-laki,Perempuan', // Validasi gender
            'weight' => 'sometimes|required|numeric',
            'height' => 'sometimes|required|numeric',
            'age' => 'sometimes|required|integer',
        ]);

        $data->username = $request->username ?? $data->username;
        $data->email = $request->email ?? $data->email;
        if ($request->has('password')) {
            $data->password = Hash::make($request->password); // Hash password jika diupdate
        }
        $data->gender = $request->gender ?? $data->gender; // Update gender jika ada
        $data->weight = $request->weight ?? $data->weight;
        $data->height = $request->height ?? $data->height;
        $data->age = $request->age ?? $data->age;
        $data->save();  

        return response()->json([
            'message' => 'Data updated successfully',
            'data' => $data
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = UserModel::find($id);

        if (!$data) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $data->delete();

        return response()->json(['message' => 'Data deleted successfully']);
    }

    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',  // password harus terkonfirmasi
            'gender' => 'required|in:Laki-laki,Perempuan',
            'weight' => 'required|numeric',
            'height' => 'required|numeric',
            'age' => 'nullable|integer',
        ]);

        // Buat pengguna baru
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash password
            'gender' => $request->gender,
            'weight' => $request->weight,
            'height' => $request->height,
            'age' => $request->age,
        ]);

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }
}