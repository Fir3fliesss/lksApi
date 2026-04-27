<?php

namespace App\Actions;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutAction
{
    /**
     * Process checkout: validate stock, create order, decrement stock, clear cart.
     */
    public function execute(User $user): Order
    {
        $cartItems = $user->cartItems()->with('product')->get();

        if ($cartItems->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => ['Your cart is empty.'],
            ]);
        }

        return DB::transaction(function () use ($user, $cartItems) {
            // Lock each product row and validate stock
            foreach ($cartItems as $item) {
                $product = Product::where('id', $item->product_id)
                    ->lockForUpdate()
                    ->first();

                if ($product->stock < $item->quantity) {
                    throw ValidationException::withMessages([
                        'stock' => ["Insufficient stock for \"{$product->name}\". Available: {$product->stock}."],
                    ]);
                }
            }

            $totalPrice = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);

            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $totalPrice,
                'shipping_address' => $user->address,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->product->price * $item->quantity,
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            $user->cartItems()->delete();

            return $order;
        });
    }
}
