<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'sale_id' => $this->sale_id,
            'user_id' => $this->user_id,
            'customer_address' => $this->sale->customer_address,
            // 'customer_name' => $this->customer->name,
            // 'customer_phone' => $this->customer->phone,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
