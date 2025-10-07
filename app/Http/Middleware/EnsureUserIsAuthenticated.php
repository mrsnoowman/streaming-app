<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            // Log unauthorized access attempt
            \Log::warning('Unauthorized access attempt', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()
            ]);

            // Redirect to login page with intended URL
            return redirect()->route('login')
                ->with('error', 'Please login to access the dashboard.');
        }

        // Check if user is active (you can add additional checks here)
        $user = Auth::user();
        
        // Log successful access
        \Log::info('Authenticated user accessing dashboard', [
            'user_id' => $user->id,
            'email' => $user->email,
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'timestamp' => now()
        ]);

        return $next($request);
    }
}
