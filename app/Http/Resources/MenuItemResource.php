<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Item ID' => $this->id,
            'Name' => $this->name,
            'Details'=>$this->when(
                $request->routeIs('menu-items.show'),
                [
                    'Description' => $this->description,
                    'Category' => $this->categories->name,
                    'Price' => $this->price,
                    'is Available' => $this->is_available,
                ],
            ),

        ];

    }
}
