<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'description' => $this->description,
            'price'       => $this->price,
            'stock'       => $this->stock,
            'sku'         => $this->sku,
            'is_active'   => $this->is_active,
            'image'       => $this->image_url,
            'categories'  => $this->whenLoaded('categories', fn() =>
                $this->categories->map(fn($c) => [
                    'id'   => $c->id,
                    'name' => $c->name,
                ])
            ),
            'created_at'  => $this->created_at->format('Y-m-d'),
        ];
    }
}
