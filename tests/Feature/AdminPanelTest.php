<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('unauthenticated admin panel', function() {
    $response = $this->get('http://localhost/Admin');
    
    $response->assertStatus(302);
    $response->assertLocation('http://localhost/Admin/login');
});

test('Aunthenticated admin panel', function () {
    $this->actingAs(User::factory()->create());
    $response = $this ->get('http://localhost/Admin');

    $response->assertStatus(200);
    $response->assertSee('Dashboard');
});