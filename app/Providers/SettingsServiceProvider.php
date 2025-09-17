<?php

namespace App\Providers;

use App\Models\Settings;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
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
    public function boot(): void
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        // Cargar configuraciones con caché (1 hora)
        $settings = Cache::remember('settings', 3600, function () {
            return Settings::pluck('value', 'key')->toArray();
        });

        // Establecer valores por defecto
        $defaults = [
            'app_name' => config('app.name', 'POS System'),
            'logo_path' => 'images/default-logo.png',
            'currency_code' => 'BOB',
            'currency_symbol' => 'Bs.',
            'locale' => config('app.locale', 'es'),
        ];

        // Combinar con valores por defecto
        $settings = array_merge($defaults, $settings);

        // Establecer en config()
        config(['settings' => $settings]);

        // Configurar idioma dinámicamente
        app()->setLocale(config('settings.locale', 'es'));
    }
}
