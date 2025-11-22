<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
        // Generar nonce único para esta request
        $nonce = base64_encode(random_bytes(16));
        $request->attributes->set('csp_nonce', $nonce);
        
        $response = $next($request);

        // Content Security Policy (CSP) - En desarrollo, deshabilitarlo para evitar problemas con Vite
        $isDevelopment = config('app.env') !== 'production';
        
        if ($isDevelopment) {
            // En desarrollo: CSP muy permisivo (casi deshabilitado)
            $cspDirectives = [
                "default-src *",
                "script-src * 'unsafe-inline' 'unsafe-eval'",
                "style-src * 'unsafe-inline'",
                "img-src * data: blob:",
                "font-src * data:",
                "connect-src *",
                "frame-src *",
            ];
        } else {
            // Producción: CSP estricto (sin unsafe-inline en scripts)
            $cspDirectives = [
                "default-src 'self'",
                // script-src: nonce para scripts inline + unsafe-eval (necesario para Alpine.js)
                "script-src 'self' 'nonce-{$nonce}' 'unsafe-eval' https://cdn.jsdelivr.net https://sdk.mercadopago.com https://www.mercadopago.com",
                // style-src: unsafe-inline necesario para Tailwind y Alpine + Bunny Fonts
                "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net https://cdn.jsdelivr.net",
                "font-src 'self' https://fonts.gstatic.com https://fonts.bunny.net data:",
                "img-src 'self' data: https: blob:",
                "connect-src 'self' https://api.mercadopago.com https://www.mercadopago.com https://fonts.bunny.net",
                "frame-src 'self' https://www.mercadopago.com",
                "frame-ancestors 'self'",
                "object-src 'none'",
                "base-uri 'self'",
                "form-action 'self'",
                "upgrade-insecure-requests",
            ];
        }
        
        $response->headers->set('Content-Security-Policy', implode('; ', $cspDirectives));

        // X-Frame-Options y X-Content-Type-Options están configurados en CloudPanel/Nginx
        // Se comentan para evitar duplicación
        // $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        // $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Otros headers de seguridad recomendados
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        // Eliminar headers que revelan información del servidor
        $response->headers->remove('Server');
        $response->headers->remove('X-Powered-By');

        return $response;
    }
}
