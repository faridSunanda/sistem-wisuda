<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SwitchRoleController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'role' => ['required', 'string', 'in:admin,akademik,keuangan,mahasiswa'],
        ]);

        $newRole = $request->role;
        
        // Check if role switching is allowed
        if (!session('allow_role_switch')) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki hak untuk berpindah role.'
            ], 403);
        }

        // Update acting_role session
        session(['acting_role' => $newRole]);

        // Determine redirect URL based on new role
        $redirectUrl = match ($newRole) {
            'admin' => route('admin.dashboard'),
            'akademik' => route('akademik.dashboard'),
            'keuangan' => route('keuangan.dashboard'),
            'mahasiswa' => route('mahasiswa.dashboard'),
            default => '/',
        };

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil diganti.',
            'redirect' => $redirectUrl,
        ]);
    }
}
