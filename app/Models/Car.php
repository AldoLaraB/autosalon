<?php

namespace App\Models;

use App\Traits\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Car extends Model
{
    use HasMedia;

    protected $fillable = [
        'user_id',
        'shop_id',
        'location_id',
        'brand_id',
        'model',
        'year',
        'price',
        'mileage',
        'fuel_type',
        'transmission',
        'is_new',
        'description',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'mileage' => 'integer',
        'is_new' => 'boolean',
        'is_active' => 'boolean',
        'year' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function primaryImage()
    {
        return $this->primaryMedia('gallery');
    }

    public function gallery()
    {
        return $this->getMediaByCollection('gallery');
    }
}
