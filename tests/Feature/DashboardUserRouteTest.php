<?php

use App\Models\User;

test('authenticated user can access dashboard', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertStatus(200);
});

test('user create routes are available and do not collide with dynamic routes', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $responseShops = $this->get(route('shops.create'));
    $responseCars = $this->get(route('cars.create'));

    $responseShops->assertStatus(200);
    $responseCars->assertStatus(200);

    $this->assertSame('/shops/create', route('shops.create', [], false));
    $this->assertSame('/cars/create', route('cars.create', [], false));
});
