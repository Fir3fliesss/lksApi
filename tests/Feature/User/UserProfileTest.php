<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('User Profile', function () {
    it('returns authenticated user profile', function () {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/me')
            ->assertOk()
            ->assertJsonStructure([
                'data' => ['id', 'name', 'username', 'email', 'address', 'created_at'],
            ]);
    });

    it('returns 401 when not authenticated', function () {
        $this->getJson('/api/v1/me')
            ->assertUnauthorized();
    });
});
