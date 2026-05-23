<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (in_array($user->role, $roles, true)) {
            return $next($request);
        }

        return redirect()
            ->route($this->dashboardRouteFor($user->role))
            ->with('error', 'No tienes permiso para acceder a esa sección.');
    }

    private function dashboardRouteFor(?string $role): string
    {
        return match ($role) {
            'admin' => 'admin.dashboard',
            'empresa' => 'empresa.dashboard',
            'alumno' => 'alumno.dashboard',
            default => 'login',
        };
    }
}
