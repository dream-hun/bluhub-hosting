<?php

namespace App\Providers;

use App\Services\OpenXML\OP_API;
use Illuminate\Support\ServiceProvider;

class OpenXMLServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('openxml', function ($app) {
            return new OP_API(
                config('services.openxml.url'),
                config('services.openxml.timeout')
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/openxml.php' => config_path('openxml.php'),
        ]);
    }
}
