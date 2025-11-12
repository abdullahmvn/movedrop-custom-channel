<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WebhookStoreRequest extends FormRequest
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
            'webhooks' => ['required', 'array', 'min:1'],
            'webhooks.*.name' => ['required', 'string', 'max:255'],
            'webhooks.*.event' => ['required', 'string', 'max:255', 'unique:webhooks,event'],
            'webhooks.*.delivery_url' => ['required', 'url', 'max:255'],
        ];
    }
}
