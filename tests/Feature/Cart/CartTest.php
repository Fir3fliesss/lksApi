<?php

use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('Cart', function () {
    it('returns empty cart for new user', function () {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/v1/cart-items')
            ->assertOk()
            ->assertJson(['data' => []]);
    });

    it('adds a product to the cart', function () {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/cart-items', [
                'product_id' => $product->id,
                'quantity' => 2,
            ])->assertStatus(201)
            ->assertJsonPath('data.quantity', 2)
            ->assertJsonPath('data.product.id', $product->id);
    });

    it('increments quantity when same product is added again', function () {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 20]);

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/cart-items', ['product_id' => $product->id, 'quantity' => 2]);

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/cart-items', ['product_id' => $product->id, 'quantity' => 3])
            ->assertStatus(201)
            ->assertJsonPath('data.quantity', 5);
    });

    it('updates cart item quantity', function () {
        $product = Product::factory()->create(['stock' => 20]);
        $user = User::factory()->create();
        CartItem::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
        $cartItem = $user->cartItems()->first();

        $this->actingAs($user, 'sanctum')
            ->putJson("/api/v1/cart-items/{$cartItem->id}", ['quantity' => 5])
            ->assertOk()
            ->assertJsonPath('data.quantity', 5);
    });

    it('removes a cart item', function () {
        $product = Product::factory()->create();
        $user = User::factory()->create();
        CartItem::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
        $cartItem = $user->cartItems()->first();

        $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/v1/cart-items/{$cartItem->id}")
            ->assertOk()
            ->assertJson(['message' => 'Item removed from cart.']);
    });

    it('cannot update another user\'s cart item', function () {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $cartItem = CartItem::factory()->create(['user_id' => $other->id]);

        $this->actingAs($user, 'sanctum')
            ->putJson("/api/v1/cart-items/{$cartItem->id}", ['quantity' => 5])
            ->assertForbidden();
    });

    it('cannot delete another user\'s cart item', function () {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $cartItem = CartItem::factory()->create(['user_id' => $other->id]);

        $this->actingAs($user, 'sanctum')
            ->deleteJson("/api/v1/cart-items/{$cartItem->id}")
            ->assertForbidden();
    });

    it('requires authentication', function () {
        $this->getJson('/api/v1/cart-items')->assertUnauthorized();
    });

    it('fails when product does not exist', function () {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->postJson('/api/v1/cart-items', ['product_id' => 99999, 'quantity' => 1])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('product_id');
    });
});
