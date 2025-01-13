<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDomainPricingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tld' => 'required|string|max:50|unique:domain_pricing,tld,'.$this->pricing->id,
            'registration_price' => 'required|numeric|min:0',
            'renewal_price' => 'required|numeric|min:0',
            'transfer_price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ];
    }
}
