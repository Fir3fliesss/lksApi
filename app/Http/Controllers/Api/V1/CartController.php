<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\UpdateCartRequest;
use App\Http\Resources\V1\CartItemResource;
use App\Models\CartItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class CartController extends Controller
{
    /**
     * List all items in the authenticated user's cart.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $items = $request->user()
            ->cartItems()
            ->with('product')
            ->latest()
            ->get();

        return CartItemResource::collection($items);
    }

    /**
     * Add a product to the cart, or increment quantity if it already exists.
     */
    public function store(AddToCartRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $cartItem = CartItem::where('user_id', $request->user()->id)
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $validated['quantity']);
            $cartItem->refresh();
        } else {
            $cartItem = CartItem::create([
                'user_id' => $request->user()->id,
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
            ]);
        }

        $cartItem->load('product');

        return (new CartItemResource($cartItem))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Update the quantity of a cart item.
     */
    public function update(UpdateCartRequest $request, CartItem $cartItem): CartItemResource
    {
        $request->user()->can('update', $cartItem) || abort(403);

        $cartItem->update($request->validated());
        $cartItem->load('product');

        return new CartItemResource($cartItem);
    }

    /**
     * Remove an item from the cart.
     */
    public function destroy(Request $request, CartItem $cartItem): JsonResponse
    {
        $request->user()->can('delete', $cartItem) || abort(403);

        $cartItem->delete();

        return response()->json(['message' => 'Item removed from cart.']);
    }
}
