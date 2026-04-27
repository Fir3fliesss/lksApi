<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    /**
     * List all orders for the authenticated user.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $orders = $request->user()
            ->orders()
            ->with('items')
            ->latest()
            ->paginate(10);

        return OrderResource::collection($orders);
    }

    /**
     * Return a single order detail.
     */
    public function show(Request $request, Order $order): OrderResource
    {
        $request->user()->can('view', $order) || abort(403);

        return new OrderResource($order->load('items'));
    }
}
