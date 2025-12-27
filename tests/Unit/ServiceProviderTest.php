<?php

it('publishes config file', function () {
    $this->artisan('vendor:publish', ['--tag' => 'clavis'])
        ->assertSuccessful();

    expect(config_path('clavis.php'))->toBeFile();

    unlink(config_path('clavis.php'));
});
