<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccessLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $level  'full' | 'partial' | 'write'
     */
    public function handle(Request $request, Closure $next, string $level = 'write'): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Restricted Access (View Only): Block all mutation HTTP methods (POST, PUT, PATCH, DELETE)
        if ($user->isRestrictedAccess() && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Akses ditolak: Akun Anda adalah Restricted Access (View Only).'], 403);
            }
            return back()->with('error', 'Akses Ditolak: Peran akun Anda adalah Restricted Access (View Only). Anda tidak memiliki izin untuk mengubah data.');
        }

        if ($level === 'full' && !$user->isFullAccess()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Akses ditolak: Fitur ini membutuhkan Full Access (BoD/Finance/GM).'], 403);
            }
            return back()->with('error', 'Akses Ditolak: Fitur ini hanya dapat diakses oleh Full Access (BoD / GM / Finance).');
        }

        if ($level === 'partial' && !($user->isFullAccess() || $user->isPartialAccess())) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Akses ditolak: Fitur ini membutuhkan Partial atau Full Access.'], 403);
            }
            return back()->with('error', 'Akses Ditolak: Anda tidak memiliki izin untuk melakukan tindakan ini.');
        }

        return $next($request);
    }
}
