<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\api_data;
use Illuminate\Support\Facades\Hash;

class APIController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = api_data::all();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:api_datas,email', // Validasi unik pada email
            'password' => 'required|string|min:6',
            'weight' => 'required|numeric',
            'height' => 'required|numeric',
            'age' => 'required|integer',
        ]);

        $data = new api_data();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->password = Hash::make($request->password); // Hash password
        $data->weight = $request->weight;
        $data->height = $request->height;
        $data->age = $request->age;
        $data->save();

        return response()->json([
            'message' => 'Data created successfully',
            'data' => $data
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = api_data::find($id);

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
        $data = api_data::find($id);

        if (!$data) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:api_datas,email,' . $id,
            'password' => 'sometimes|required|string|min:6',
            'weight' => 'sometimes|required|numeric',
            'height' => 'sometimes|required|numeric',
            'age' => 'sometimes|required|integer',
        ]);

        $data->name = $request->name ?? $data->name;
        $data->email = $request->email ?? $data->email;
        if ($request->has('password')) {
            $data->password = Hash::make($request->password); // Hash password if updated
        }
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
        $data = api_data::find($id);

        if (!$data) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $data->delete();

        return response()->json(['message' => 'Data deleted successfully']);
    }
}
