<?php

use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;

it('blocks request without api token', function (): void {
    $response = $this->getJson('/api/test');

    $response
        ->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});

it('blocks request when hash is null', function (): void {
    Config::set('clavis.hash');

    $response = $this->withToken('any-token')->getJson('/api/test');

    $response
        ->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});

it('blocks request when hash is empty string', function (): void {
    Config::set('clavis.hash', '');

    $response = $this->withToken('any-token')->getJson('/api/test');

    $response
        ->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});

it('blocks request when hash is invalid base64 string', function (): void {
    Config::set('clavis.hash', 'not-valid-base64!!!');

    $response = $this->withToken('any-token')->getJson('/api/test');

    $response
        ->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});

it('blocks request with invalid api token', function (): void {
    Config::set('clavis.hash', base64_encode(Hash::make('valid-token')));

    $response = $this->withToken('invalid-token')->getJson('/api/test');

    $response
        ->assertStatus(401)
        ->assertJson(['message' => 'Unauthenticated.']);
});

it('allows request with valid api token', function (): void {
    Config::set('clavis.hash', base64_encode(Hash::make('valid-token')));

    $response = $this->withToken('valid-token')->getJson('/api/test');

    $response
        ->assertStatus(200)
        ->assertJson(['success' => true]);
});

it('dispatches Failed event with null token when no bearer provided', function (): void {
    Event::fake([Failed::class]);

    $this->getJson('/api/test');

    Event::assertDispatched(Failed::class, function (Failed $event): bool {
        return $event->guard === 'clavis'
               && $event->credentials['token'] === null;
    });
});

it('dispatches Failed event with masked token on invalid attempt', function (): void {
    Event::fake([Failed::class]);

    Config::set('clavis.hash', base64_encode(Hash::make('valid-token')));

    $this->withToken('invalid-token')->getJson('/api/test');

    Event::assertDispatched(Failed::class, function (Failed $event): bool {
        return $event->guard === 'clavis'
               && $event->credentials['token'] === '*********oken';
    });
});

it('works via string alias "clavis"', function (): void {
    Config::set('clavis.hash', base64_encode(Hash::make('valid-token')));

    $response = $this->withToken('valid-token')->getJson('/api/test-alias');

    $response
        ->assertStatus(200)
        ->assertJson(['success' => true]);
});
