<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'brand' => $this->whenLoaded('brand', function () {
                return [
                    'id' => $this->brand->id,
                    'name' => $this->brand->name,
                ];
            }),
            'model' => $this->model,
            'year' => $this->year,
            'price' => $this->price,
            'mileage' => $this->mileage,
            'fuel_type' => $this->fuel_type,
            'transmission' => $this->transmission,
            'is_new' => $this->is_new,
            'description' => $this->description,
            'primary_image' => $this->when($this->primaryImage(), function () {
                return [
                    'url' => $this->primaryImage()->url,
                    'thumb_url' => $this->primaryImage()->getUrl('thumb'),
                ];
            }),
            'gallery' => $this->whenLoaded('gallery', function () {
                return $this->gallery->map(function ($image) {
                    return [
                        'url' => $image->url,
                        'thumb_url' => $image->getUrl('thumb'),
                    ];
                });
            }),
            'shop' => $this->whenLoaded('shop', function () {
                return [
                    'id' => $this->shop->id,
                    'name' => $this->shop->name,
                ];
            }),
            'location' => $this->whenLoaded('location', function () {
                return [
                    'id' => $this->location->id,
                    'city' => $this->location->city,
                    'address' => $this->location->address,
                ];
            }),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
