<?php

namespace App\Http\Middleware;

use App\Modules\Core\Models\WebService;
use Closure;

class ServiceMiddleware
{
    public function handle($request, Closure $next)
    {
        $service_token = $request->header('Service-Token');
        if (!$service_token) {
            return response()->json(['message' => 'Service token required'], 401);
        }

        $webService = WebService::findCached($service_token, 'token');

        if (!$webService) {
            return response()->json(['message' => 'Service token expired'], 401);
        }

        app()->instance(WebService::class, $webService);
        return $next($request);
    }
}
