<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'price' => $this->price,
            'stock' => $this->stock,
            'category' => $this->category->name,
            'image' => $this->image ? asset('storage/' . $this->image) : "https://www.mon-site-bug.fr/uploads/products/default-product.png",
            'barcode' => $this->barcode,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
