<?php

use Illuminate\Support\Facades\Config;

it('blocks request without api token', function () {
    $response = $this->getJson('/api/test');

    $response
        ->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});

it('blocks when hash is null', function () {
    Config::set('clavis.hash');

    $response = $this->withToken('any-token')->getJson('/api/test');

    $response
        ->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});

it('blocks request with invalid api token', function () {
    Config::set('clavis.hash', base64_encode(Hash::make('valid-token')));

    $response = $this->withToken('invalid-token')->getJson('/api/test');

    $response
        ->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});

it('allows request with valid api token', function () {
    Config::set('clavis.hash', base64_encode(Hash::make('valid-token')));

    $response = $this->withToken('valid-token')->getJson('/api/test');

    $response
        ->assertStatus(200)
        ->assertJson(['success' => true]);
});
