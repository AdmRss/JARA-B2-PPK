<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Memvalidasi bahwa request hanya dapat diakses oleh Administrator.
     * Disesuaikan dengan design.md (role === 'admin') dan backward-compatible.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek autentikasi user
        if (!auth()->check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated. Silakan login terlebih dahulu.'
                ], 401);
            }

            // Jika route login tersedia, arahkan ke route login
            if (\Illuminate\Support\Facades\Route::has('login')) {
                return redirect()->guest(route('login'))->with('error', 'Silakan login sebagai Admin terlebih dahulu.');
            }

            // Fallback response 403 jika belum ada sistem route login lengkap
            abort(403, 'Akses Ditolak: Anda harus login sebagai Administrator untuk mengakses halaman ini.');
        }

        $user = auth()->user();

        // 2. Cek apakah user memiliki peran Admin sesuai rancangan design.md ('role' => 'admin')
        $isAdmin = false;
        if (isset($user->role) && $user->role === 'admin') {
            $isAdmin = true;
        } elseif (isset($user->is_admin) && (bool)$user->is_admin) {
            $isAdmin = true;
        }

        if (!$isAdmin) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Forbidden. Anda tidak memiliki hak akses administrator.'
                ], 403);
            }

            abort(403, 'Akses Ditolak (403 Forbidden): Hanya Administrator yang diizinkan mengakses menu ini.');
        }

        return $next($request);
    }
}
