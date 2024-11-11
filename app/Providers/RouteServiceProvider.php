<?php

namespace App\Providers;

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
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });

        // Redirect ke dashboard sesuai role setelah login
        Route::middleware(['auth', 'verified'])->group(function () {
            Route::get('/dashboard', function () {
                if (auth()->user()->role === 'admin') {
                    return redirect()->route('admin.dashboard');
                } elseif (auth()->user()->role === 'pemohon') {
                    return redirect()->route('pemohon.dashboard');
                } elseif (auth()->user()->role === 'tu') {
                    return redirect()->route('tu.dashboard');
                } elseif (auth()->user()->role === 'dekan') {
                    return redirect()->route('dekan.dashboard');
                } elseif (auth()->user()->role === 'keuangan') {
                    return redirect()->route('keuangan.dashboard');
                }
            })->name('dashboard');
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
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
