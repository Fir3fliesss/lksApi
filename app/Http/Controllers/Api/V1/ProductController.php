<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    /**
     * Return a paginated list of all products.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->integer('per_page', 5);
        $perPage = $perPage > 100 ? 100 : ($perPage < 1 ? 5 : $perPage);

        $products = Product::latest()->paginate($perPage);

        return ProductResource::collection($products);
    }

    /**
     * Return a single product.
     */
    public function show(Product $product): ProductResource
    {
        return new ProductResource($product);
    }
}
