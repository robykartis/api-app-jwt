<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MiddlewareUserAksess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Pengecekan peran pengguna
        if ($user && in_array($user->role_id, [1, 2, 3])) {
            return $next($request);
        }

        return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401);
    }
}
