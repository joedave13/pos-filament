<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'email' => $this->email,
            'gender' => $this->gender,
            'phone' => $this->phone,
            'total_price' => $this->total_price,
            'note' => $this->note,
            'payment_method' => new PaymentMethodResource($this->whenLoaded('paymentMethod')),
            'paid_amount' => $this->paid_amount,
            'change_amount' => $this->change_amount,
            'created_at' => $this->created_at->format('d M Y H:i:s'),
            'order_details' => OrderDetailResource::collection($this->whenLoaded('orderDetails'))
        ];
    }
}
