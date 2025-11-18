<?php

namespace App\Providers;

use App\Models\CashMovement;
use App\Models\StockMovement;
use App\Observers\CashMovementObserver;
use App\Observers\StockMovementObserver;
use Illuminate\Support\Facades\URL;
use App\Models\Settings;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use App\Policies\SettingsPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Settings::class => SettingsPolicy::class,
        Permission::class => PermissionPolicy::class,
        Role::class => RolePolicy::class,
    ];

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
        $this->registerPolicies();

        StockMovement::observe(StockMovementObserver::class);
        CashMovement::observe(CashMovementObserver::class);

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
