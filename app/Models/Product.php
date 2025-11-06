<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
