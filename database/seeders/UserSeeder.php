<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Menambahkan data dummy pengguna
        User::create([
            'username' => 'john_doe',
            'email' => 'john.doe@example.com',
            'password' => Hash::make('password123'),
            'gender' => 'Male',
            'weight' => 75.5,
            'height' => 180.0,
            'age' => 28,
        ]);

        User::create([
            'username' => 'jane_doe',
            'email' => 'jane.doe@example.com',
            'password' => Hash::make('password123'),
            'gender' => 'Female',
            'weight' => 65.0,
            'height' => 165.0,
            'age' => 25,
        ]);

        User::create([
            'username' => 'mark_smith',
            'email' => 'mark.smith@example.com',
            'password' => Hash::make('password123'),
            'gender' => 'Male',
            'weight' => 82.0,
            'height' => 178.0,
            'age' => 30,
        ]);
    }
}
