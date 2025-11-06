<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductProperty extends Model
{
    protected $guarded = ['id'];

    public function propertyValues(): HasMany
    {
        return $this->hasMany(ProductPropertyValue::class);

    }
}
