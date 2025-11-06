<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariationProperty extends Model
{
    protected $guarded = ['id'];

    public function property(): BelongsTo
    {
        return $this->belongsTo(ProductProperty::class, 'product_property_id');
    }

    public function propertyValue(): BelongsTo
    {
        return $this->belongsTo(ProductPropertyValue::class, 'product_property_value_id');
    }
}
