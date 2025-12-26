<?php

declare(strict_types=1);

namespace SKulich\LaravelClavis;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

final class ServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            // $this->commands([ClavisKeyCommand::class]);
        }

        $this->publishes([__DIR__.'/../config/clavis.php' => config_path('clavis.php')], ['clavis']);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/clavis.php', 'clavis');
    }
}
