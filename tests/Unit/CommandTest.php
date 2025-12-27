<?php

use SKulich\LaravelClavis\Console\ClavisKeyCommand;

it('succeeds with confirmation', function () {
    $this->artisan(ClavisKeyCommand::class)
        ->expectsConfirmation('Are you sure you want to run this command?', 'yes')
        ->assertSuccessful();
});

it('fails without confirmation', function () {
    Config::set('clavis.key', 'any-key');
    $this->artisan(ClavisKeyCommand::class)
        ->expectsConfirmation('Are you sure you want to run this command?', 'no')
        ->assertFailed();
});

it('generates clavis key', function () {
    // First Arrange
    $zeroKey = config('clavis.key');

    // First Assert
    expect($zeroKey)->toBeNull();

    // First Act
    $this->artisan(ClavisKeyCommand::class, ['--force' => true])
        ->assertSuccessful();

    // First Arrange
    $firstKey = config('clavis.key');

    // First Assert
    expect($firstKey)->not->toBeNull();

    // Second Act
    $this->artisan(ClavisKeyCommand::class, ['--force' => true])
        ->assertSuccessful();

    // Second Arrange
    $secondKey = config('clavis.key');

    // Second Assert
    expect($secondKey)->not->toBeNull()
        ->and($secondKey)->not->toBe($firstKey);
});
