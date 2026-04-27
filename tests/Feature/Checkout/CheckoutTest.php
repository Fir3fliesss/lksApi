<?php

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('Checkout', function () {
    it('creates an order from cart and clears the cart', function () {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 100000, 'stock' => 10]);
        CartItem::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/checkout')
            ->assertStatus(201)
            ->assertJsonPath('data.total_price', 200000)
            ->assertJsonStructure([
                'data' => [
                    'id', 'total_price', 'shipping_address',
                    'items' => [['id', 'product_name', 'price', 'quantity', 'subtotal']],
                ],
            ]);

        // Cart should be empty
        expect($user->cartItems()->count())->toBe(0);

        // Stock should be decremented
        expect($product->fresh()->stock)->toBe(8);
    });

    it('returns 422 when cart is empty', function () {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/checkout')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('cart');
    });

    it('returns 422 when product stock is insufficient', function () {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 1]);
        CartItem::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 5,
        ]);

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/checkout')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('stock');
    });

    it('requires authentication', function () {
        $this->postJson('/api/v1/checkout')->assertUnauthorized();
    });
});
