<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    // app/Providers/RouteServiceProvider.php

    public function boot()
    {
        Route::prefix('api')->middleware('api')->group(base_path('routes/api.php'));

        Route::middleware('web')->group(base_path('routes/web.php'));
    }
}
