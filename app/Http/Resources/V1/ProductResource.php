<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'price' => $this->price,
            'stock' => $this->stock,
            'in_stock' => $this->stock > 0,
            'created_at' => $this->created_at,
        ];
    }
}
