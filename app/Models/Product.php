<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'tags' => 'array',
    ];

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function media(): HasMany
    {
        return $this->hasMany(ProductMedia::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(ProductProperty::class);
    }
}
