<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LaravelSecurityMaintenance
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Añadir encabezados personalizados
        $response->headers->set('X-Developer', 'franklinnot');
        $response->headers->set('X-Developer-Contact', 'franklinnot@outlook.com');

        // Opcional: Añadir información al JSON de la respuesta
        if ($response instanceof \Illuminate\Http\JsonResponse) {
            $data = $response->getData(true);
            $data['_developer'] = [
                'name' => 'franklinnot',
                'contact' => 'franklinnot@outlook.com',
                'github' => 'https://github.com/franklinnot',
                'linkedin' => 'https://www.linkedin.com/in/franklinnot/'
            ];
            $response->setData($data);
        }

        return $response;
    }
}
