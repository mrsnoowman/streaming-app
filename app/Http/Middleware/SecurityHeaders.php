<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Security Headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        
        // Content Security Policy
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' cdn.jsdelivr.net; " .
               "style-src 'self' 'unsafe-inline' fonts.googleapis.com cdn.jsdelivr.net; " .
               "font-src 'self' fonts.gstatic.com cdn.jsdelivr.net; " .
               "img-src 'self' data: https:; " .
               "media-src 'self' https:; " .
               "connect-src 'self' https:; " .
               "frame-src 'none'; " .
               "object-src 'none'; " .
               "base-uri 'self';";
        
        $response->headers->set('Content-Security-Policy', $csp);
        
        // HSTS (HTTP Strict Transport Security) - Only for HTTPS
        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
