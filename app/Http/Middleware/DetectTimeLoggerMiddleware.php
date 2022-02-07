<?php

namespace App\Http\Middleware;

use App\Services\TimeLogger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class DetectTimeLoggerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $routeName = Route::getCurrentRoute()->getActionName();
        $timeLogger = new TimeLogger($routeName);
        $timeLogger->start();

        $response = $next($request);

        $timeLogger->end();

        return $response;
    }
}
