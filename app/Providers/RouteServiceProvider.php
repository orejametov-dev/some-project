<?php

namespace App\Providers;

use Alifuz\Utils\Gateway\Entities\GatewayApplication;
use Alifuz\Utils\Gateway\Middlewares\GatewayMiddleware;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware(['api', 'access-token', 'time-logger'])
                ->namespace($this->namespace)
                ->group(base_path('routes/api_gate.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            Route::prefix('gateway')
                ->middleware(['api', GatewayMiddleware::class, 'gateway-auth-user'])
                ->namespace($this->namespace)
                ->group(base_path('routes/gateway.php'));

            Route::prefix('gateway-merchant')
                ->middleware(['api', GatewayMiddleware::class, 'gateway-auth-user'])
                ->namespace($this->namespace)
                ->group(base_path('routes/gateway_merchant.php'));

            Route::prefix('gateway/credits')
                ->middleware(['api', GatewayMiddleware::class, 'gateway-auth-user'])
                ->namespace($this->namespace)
                ->group(base_path('routes/gateway_credits.php'));

            Route::prefix('gateway-compliance')
                ->middleware(['api', GatewayMiddleware::class, 'gateway-auth-user'])
                ->namespace($this->namespace)
                ->group(base_path('routes/gateway_compliance.php'));

            Route::prefix('gateway-calls')
                ->middleware(['api', GatewayMiddleware::class, 'gateway-auth-user'])
                ->namespace($this->namespace)
                ->group(base_path('routes/gateway_calls.php'));

            Route::prefix('gateway/law')
                ->middleware(['api', GatewayMiddleware::class, 'gateway-auth-user'])
                ->namespace($this->namespace)
                ->group(base_path('routes/gateway_law.php'));

            Route::prefix('gateway/online')
                ->middleware(['api', GatewayMiddleware::class, 'gateway-auth-user'])
                ->namespace($this->namespace)
                ->group(base_path('routes/gateway_online.php'));

            Route::prefix('gateway/report')
                ->middleware(['api', GatewayMiddleware::class, 'gateway-auth-user'])
                ->namespace($this->namespace)
                ->group(base_path('routes/gateway_report.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
