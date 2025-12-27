<?php

use Illuminate\Support\Facades\Config;

it('blocks request without api key', function () {
    $response = $this->getJson('/api/test');

    $response
        ->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});

it('blocks when master key is null', function () {
    Config::set('clavis.key');

    $response = $this->withToken('any-key')->getJson('/api/test');

    $response
        ->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});

it('blocks request with invalid api key', function () {
    Config::set('clavis.key', base64_encode(Hash::make('valid-key')));

    $response = $this->withToken('invalid-key')->getJson('/api/test');

    $response
        ->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});

it('allows request with valid api key', function () {
    Config::set('clavis.key', base64_encode(Hash::make('valid-key')));

    $response = $this->withToken('valid-key')->getJson('/api/test');

    $response
        ->assertStatus(200)
        ->assertJson(['success' => true]);
});
