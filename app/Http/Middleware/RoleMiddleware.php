<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Protect routes based on user roles: Admin, Doctor, Pharmacist, Receptionist
     * (Supports both English and Indonesian role identifiers).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Ensure user is authenticated
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $userRole = strtolower(trim($request->user()->role ?? ''));

        // 2. Define standard role mapping (English <-> Indonesian)
        $roleMapping = [
            'admin'        => ['admin'],
            'doctor'       => ['doctor', 'dokter'],
            'dokter'       => ['doctor', 'dokter'],
            'pharmacist'   => ['pharmacist', 'apoteker'],
            'apoteker'     => ['pharmacist', 'apoteker'],
            'receptionist' => ['receptionist', 'resepsionis'],
            'resepsionis'  => ['receptionist', 'resepsionis'],
        ];

        // 3. Build array of allowed roles
        $allowedRoles = [];
        foreach ($roles as $role) {
            $normalizedRole = strtolower(trim($role));
            $allowedRoles[] = $normalizedRole;

            if (isset($roleMapping[$normalizedRole])) {
                $allowedRoles = array_merge($allowedRoles, $roleMapping[$normalizedRole]);
            }
        }

        // 4. Check if current user role matches allowed roles
        if (!in_array($userRole, $allowedRoles, true)) {
            abort(403, 'Akses Ditolak. Anda tidak memiliki wewenang untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
