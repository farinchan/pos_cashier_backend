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
            'total' => $this->total,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'product' => $this->TransactionDetail?->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'transaction_id' => $detail->transaction_id,
                    'product_id' => $detail->product_id,
                    'quantity' => $detail->quantity,
                    'price' => $detail->price,
                    'total' => $detail->quantity * $detail->price,
                ];
            }),
            'payment' => [
                'id' => $this->TransactionPayment?->id,
                'paid' => $this->TransactionPayment?->paid,
                'change' => $this->TransactionPayment?->change,
            ],
            'cashier' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'username' => $this->user?->username,
                'email' => $this->user?->email,
            ]
        ];
    }
}
