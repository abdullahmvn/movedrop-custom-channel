<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'images' => ['required', 'array', 'min:1'],
            'images.*.is_default' => ['required', 'boolean'],
            'images.*.src' => ['required', 'url'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string', 'max:50'],
            'properties' => ['nullable', 'array'],
            'properties.*.name' => ['required', 'string', 'max:100'],
            'properties.*.values' => ['required', 'array', 'min:1'],
            'properties.*.values.*' => ['string', 'max:100'],
        ];
    }
}
