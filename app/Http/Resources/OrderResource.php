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
            "Order ID" => $this->id,
            "User ID" => $this->user_id,
            "Table ID" => $this->table_id,
            "Status" => $this->status,
            "Items" => $this->when(
                $request->routeIs('orders.show'),
                $this->orderItems->map(function ($item) {
                    return [
                        "Item ID" => $item->menuItems?->id,
                        "Item Name" => $item->menuItems?->name,
                        "Price" => $item->menuItems?->price,
                        "Quantity" => $item->quantity,
                    ];
                }),
            ),

            "Total Price" =>$this->when(
                $request->routeIs('orders.show'),
                $this->orderItems->sum(function ($item) {
                    return ($item->menuItems?->price ?? 0) * ($item->quantity ?? 1);
                }))

        ];

    }
}
