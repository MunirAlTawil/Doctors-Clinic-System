<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! Auth::check()) {
            abort(403);
        }

        $user = $request->user();

        if (! in_array($user->role, $roles, true)) {
            abort(403);
        }

        if (! $user->is_active) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'Your account is blocked.']);
        }

        return $next($request);
    }
}
