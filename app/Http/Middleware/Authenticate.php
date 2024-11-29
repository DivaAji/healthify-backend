<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Cek apakah rute yang diminta adalah login atau registrasi
        if ($request->is('api/login') || $request->is('api/user')) {
            return null;  // Tidak redirect ke login untuk rute ini
        }

        // Untuk rute lain, tetap arahkan ke login
        return $request->expectsJson() ? null : route('login');
    }
}
