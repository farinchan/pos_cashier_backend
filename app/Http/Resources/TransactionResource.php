<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'quantity' => $this->quantity,
            'product' => $this->product->name,
            'total' => $this->quantity * $this->product->price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'product' => $this->TransactionDetail->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'transaction_id' => $detail->transaction_id,
                    'product_id' => $detail->product_id,
                    'quantity' => $detail->quantity,
                    'price' => $detail->product->price,
                    'total' => $detail->quantity * $detail->product->price,
                ];
            }),
            'payment' => [
                'id' => $this->payment->id,
                'paid' => $this->payment->paid,
                'change' => $this->payment->change,
            ],
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'username' => $this->user->username,
                'email' => $this->user->email,
            ]
        ];
    }
}
