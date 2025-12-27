<?php

use SKulich\LaravelClavis\Http\Middleware\Clavis;

it('publishes config file', function () {
    $this->artisan('vendor:publish', ['--tag' => 'clavis'])
        ->assertSuccessful();

    expect(config_path('clavis.php'))->toBeFile();

    unlink(config_path('clavis.php'));
});

it('registers clavis middleware', function () {
    $this->assertEquals(
        Clavis::class,
        app('router')->getMiddleware()['clavis'] ?? null
    );
});
