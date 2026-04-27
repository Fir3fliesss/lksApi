<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('Login', function () {
    it('logs in with correct credentials and returns a token', function () {
        $user = User::factory()->create(['password' => bcrypt('secret123')]);

        $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ])->assertOk()
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email'],
                'token',
            ]);
    });

    it('returns 422 with wrong password', function () {
        $user = User::factory()->create(['password' => bcrypt('correct')]);

        $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'wrong',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('email');
    });

    it('returns 422 when email does not exist', function () {
        $this->postJson('/api/v1/login', [
            'email' => 'nobody@example.com',
            'password' => 'password',
        ])->assertUnprocessable();
    });

    it('requires email and password', function () {
        $this->postJson('/api/v1/login', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email', 'password']);
    });
});

describe('Logout', function () {
    it('logs out authenticated user and revokes token', function () {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/logout')
            ->assertOk()
            ->assertJson(['message' => 'Logged out successfully.']);

        // Token should be deleted from the database
        expect($user->tokens()->count())->toBe(0);
    });

    it('returns 401 when not authenticated', function () {
        $this->postJson('/api/v1/logout')
            ->assertUnauthorized();
    });
});
