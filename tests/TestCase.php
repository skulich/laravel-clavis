<?php

namespace SKulich\LaravelClavis\Tests;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase as BaseTestCase;
use SKulich\LaravelClavis\Http\Middleware\Clavis;
use SKulich\LaravelClavis\ServiceProvider;

abstract class TestCase extends BaseTestCase
{
    private string $tmp = __DIR__.DIRECTORY_SEPARATOR.'tmp';

    private string $env;

    protected function getEnvironmentSetUp($app): void
    {
        $this->env = '.env.'.Str::random(32);
        copy(base_path('.env.example'), $this->tmp.DIRECTORY_SEPARATOR.$this->env);

        $app->useEnvironmentPath($this->tmp);
        $app->loadEnvironmentFrom($this->env);

        $app['config']->set('app.env', 'testing');
    }

    protected function tearDown(): void
    {
        unlink($this->tmp.DIRECTORY_SEPARATOR.$this->env);

        parent::tearDown();
    }

    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
        ];
    }

    protected function defineRoutes($router): void
    {
        Route::get('/api/test', function () {
            return response()->json(['success' => true]);
        })->middleware(Clavis::class);
    }
}
