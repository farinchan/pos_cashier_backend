<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // 'products' => $this->products->map(function ($product) {
            //     return [
            //         'id' => $product->id,
            //         'name' => $product->name,
            //         'description' => $product->description,
            //         'price' => $product->price,
            //         'stock' => $product->stock,
            //         'image' => $this->image ? asset('storage/' . $this->image) : "https://www.mon-site-bug.fr/uploads/products/default-product.png",
            //         'barcode' => $product->barcode,
            //         'created_at' => $product->created_at,
            //         'updated_at' => $product->updated_at,
            //     ];
            // }),
        ];
    }
}
