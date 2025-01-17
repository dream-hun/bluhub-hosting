<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDomainPricingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tld' => 'required',
            'string',
            'max:50',
            Rule::unique('domain_pricing', 'tld')->ignore($this->pricing?->id),
            'registration_price' => 'required|numeric|min:0',
            'renewal_price' => 'required|numeric|min:0',
            'transfer_price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ];
    }
}
