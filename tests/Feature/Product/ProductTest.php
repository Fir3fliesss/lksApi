<?php

use App\Models\Product;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

describe('Products', function () {
    it('returns paginated product list without authentication', function () {
        Product::factory()->count(5)->create();

        $this->getJson('/api/v1/products')
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'description', 'image', 'price', 'stock', 'in_stock'],
                ],
                'meta' => ['current_page', 'per_page', 'total'],
                'links',
            ]);
    });

    it('returns a single product without authentication', function () {
        $product = Product::factory()->create();

        $this->getJson("/api/v1/products/{$product->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $product->id)
            ->assertJsonPath('data.name', $product->name);
    });

    it('returns in_stock true when stock is greater than zero', function () {
        $product = Product::factory()->create(['stock' => 10]);

        $this->getJson("/api/v1/products/{$product->id}")
            ->assertJsonPath('data.in_stock', true);
    });

    it('returns in_stock false when stock is zero', function () {
        $product = Product::factory()->outOfStock()->create();

        $this->getJson("/api/v1/products/{$product->id}")
            ->assertJsonPath('data.in_stock', false);
    });

    it('returns 404 for a non-existent product', function () {
        $this->getJson('/api/v1/products/99999')
            ->assertNotFound();
    });
});
