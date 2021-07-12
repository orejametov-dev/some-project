<?php

namespace App\Http\Middleware;

use App\Services\TimeLoger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class DetectTimeLoggerMiddleware
{
//    private $timeLogger;
//
//    public function __construct(TimeLoger $timeLoger)
//    {
//        $this->timeLogger = $timeLoger;
//    }

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
        $timeLogger = new TimeLoger($routeName);
        $timeLogger->start();

        $response = $next($request);

        $timeLogger->end();
        return $response;
    }
}
