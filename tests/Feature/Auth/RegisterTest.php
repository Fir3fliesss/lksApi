<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('Register', function () {
    it('registers a new user and returns a token', function () {
        $response = $this->postJson('/api/v1/register', [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'address' => 'Jl. Contoh No. 1, Jakarta',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'user' => ['id', 'name', 'username', 'email', 'address'],
                'token',
            ]);
    });

    it('fails when email is already taken', function () {
        User::factory()->create(['email' => 'taken@example.com', 'username' => 'existing']);

        $this->postJson('/api/v1/register', [
            'name' => 'Jane',
            'username' => 'janedoe',
            'email' => 'taken@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'address' => 'Jl. Test',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('email');
    });

    it('fails when username is already taken', function () {
        User::factory()->create(['username' => 'takenuser']);

        $this->postJson('/api/v1/register', [
            'name' => 'Jane',
            'username' => 'takenuser',
            'email' => 'unique@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'address' => 'Jl. Test',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('username');
    });

    it('fails when passwords do not match', function () {
        $this->postJson('/api/v1/register', [
            'name' => 'Jane',
            'username' => 'janedoe',
            'email' => 'jane@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different',
            'address' => 'Jl. Test',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('password');
    });

    it('fails when required fields are missing', function () {
        $this->postJson('/api/v1/register', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'username', 'email', 'password', 'address']);
    });
});
