<?php

namespace App\Providers;

use App\Models\CashMovement;
use App\Models\StockMovement;
use App\Observers\CashMovementObserver;
use App\Observers\StockMovementObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('production')) {
            URL::forceRootUrl(config('app.url'));
        }
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        StockMovement::observe(StockMovementObserver::class);
        CashMovement::observe(CashMovementObserver::class);
    }
}
