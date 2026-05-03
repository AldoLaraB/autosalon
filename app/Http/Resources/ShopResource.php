<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'phone' => $this->phone,
            'email' => $this->email,
            'logo' => $this->when($this->logo(), function () {
                return [
                    'url' => $this->logo()->url,
                    'thumb_url' => $this->logo()->getUrl('thumb'),
                ];
            }),
            'locations' => $this->whenLoaded('locations', function () {
                return $this->locations->map(function ($location) {
                    return [
                        'id' => $location->id,
                        'address' => $location->address,
                        'city' => $location->city,
                        'province' => $location->province,
                    ];
                });
            }),
            'cars_count' => $this->whenCounted('cars'),
            'recent_cars' => $this->whenLoaded('cars', function () {
                return CarResource::collection($this->cars);
            }),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
