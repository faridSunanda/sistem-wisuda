<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureWisudawanVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->role === 'mahasiswa') {
            $biodata = $user->biodata;
            
            if ($biodata && $biodata->is_verified_akademik) {
                return $next($request);
            }

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akses ditolak. Data Anda belum diverifikasi oleh Akademik.'], 403);
            }

            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Akses ditolak. Data biodata, sertifikat, dan formulir hanya dapat diakses setelah data wisudawan diverifikasi oleh Akademik.');
        }

        return $next($request);
    }
}
