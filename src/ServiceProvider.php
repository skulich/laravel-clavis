<?php

declare(strict_types=1);

namespace SKulich\LaravelClavis;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use SKulich\LaravelClavis\Console\ClavisKeyCommand;
use SKulich\LaravelClavis\Http\Middleware\Clavis;

final class ServiceProvider extends BaseServiceProvider
{
    public function boot(Router $router): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([ClavisKeyCommand::class]);
        }

        $this->publishes([__DIR__.'/../config/clavis.php' => config_path('clavis.php')], ['clavis']);

        $router->aliasMiddleware('clavis', Clavis::class);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/clavis.php', 'clavis');
    }
}
