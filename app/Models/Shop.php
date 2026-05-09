<?php

namespace App\Models;

use App\Traits\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shop extends Model
{
    use HasMedia;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'is_active',
        'phone',
        'email',
        'theme',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }

    public function logo()
    {
        return $this->primaryMedia('logo');
    }

    public function cover()
    {
        return $this->primaryMedia('cover');
    }
}
