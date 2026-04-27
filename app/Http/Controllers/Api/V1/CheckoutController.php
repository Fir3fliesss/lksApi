<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\CheckoutAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\OrderResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Process checkout for the authenticated user's cart.
     */
    public function __invoke(Request $request, CheckoutAction $checkout): JsonResponse
    {
        $order = $checkout->execute($request->user());

        return (new OrderResource($order->load('items')))
            ->response()
            ->setStatusCode(201);
    }
}
