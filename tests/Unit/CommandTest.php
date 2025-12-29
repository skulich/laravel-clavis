<?php

use SKulich\LaravelClavis\Console\ClavisTokenCommand;

it('succeeds with confirmation', function () {
    $this->artisan(ClavisTokenCommand::class)
        ->expectsConfirmation('Are you sure you want to run this command?', 'yes')
        ->assertSuccessful();
});

it('fails without confirmation', function () {
    Config::set('clavis.hash', 'any-token');
    $this->artisan(ClavisTokenCommand::class)
        ->expectsConfirmation('Are you sure you want to run this command?', 'no')
        ->assertFailed();
});

it('generates clavis token', function () {
    // First Arrange
    $zeroHash = config('clavis.hash');

    // First Assert
    expect($zeroHash)->toBeNull();

    // First Act
    $this->artisan(ClavisTokenCommand::class, ['--force' => true])
        ->assertSuccessful();

    // First Arrange
    $firstHash = config('clavis.hash');

    // First Assert
    expect($firstHash)->not->toBeNull();

    // Second Act
    $this->artisan(ClavisTokenCommand::class, ['--force' => true])
        ->assertSuccessful();

    // Second Arrange
    $secondHash = config('clavis.hash');

    // Second Assert
    expect($secondHash)->not->toBeNull()
        ->and($secondHash)->not->toBe($firstHash);
});
