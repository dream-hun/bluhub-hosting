<?php

namespace App\Providers;

use App\Services\EppService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(EppService::class, function ($app) {
            return new EppService([
                'username' => config('services.epp.username'),
                'password' => config('services.epp.password'),
                'server' => config('services.epp.server'),
                'port' => config('services.epp.port'),
                'certificate' => config('services.epp.certificate'),
                'ssl' => config('services.epp.ssl', true),
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    }
}
