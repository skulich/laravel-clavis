<?php

use SKulich\LaravelClavis\Console\ClavisTokenCommand;

it('generates a token via CLI and successfully authenticates API requests', function () {
    $token = Str::random(32);

    Str::createRandomStringsUsing(fn () => $token);

    $this->artisan(ClavisTokenCommand::class, ['--force' => true])
        ->assertSuccessful();

    Str::createRandomStringsNormally();

    $response = $this->withToken($token)->getJson('/api/test');

    $response
        ->assertStatus(200)
        ->assertJson(['success' => true]);
});
