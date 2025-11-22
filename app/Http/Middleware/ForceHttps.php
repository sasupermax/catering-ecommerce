<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Solo forzar HTTPS si NO es localhost (local development)
        $host = $request->getHost();
        $isLocalhost = str_contains($host, 'localhost') || str_contains($host, '127.0.0.1');
        
        if (!$request->secure() && !$isLocalhost) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
