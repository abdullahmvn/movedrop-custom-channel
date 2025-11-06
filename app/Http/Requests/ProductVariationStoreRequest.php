<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductVariationStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'variations' => ['required', 'array', 'min:1'],
            'variations.*.sku' => ['required', 'string', 'max:255'],
            'variations.*.regular_price' => ['required', 'numeric', 'gt:0.01'],
            'variations.*.sale_price' => ['nullable', 'numeric', 'min:0.01'],
            'variations.*.date_on_sale_from' => ['nullable', 'date'],
            'variations.*.date_on_sale_to' => ['nullable', 'date'],
            'variations.*.stock_quantity' => ['required', 'integer', 'min:1'],
            'variations.*.image' => ['required', 'url'],
            'variations.*.properties' => ['nullable', 'array', 'min:1'],
            'variations.*.properties.*.name' => ['required', 'string', 'max:255', 'exists:product_properties,name'],
            'variations.*.properties.*.value' => ['required', 'string', 'max:255', 'exists:product_property_values,value'],
        ];
    }
}
