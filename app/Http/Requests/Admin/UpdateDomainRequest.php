<?php

namespace App\Http\Requests\Admin;

use Gate;

class UpdateDomainRequest
{
    public function authorize(): bool
    {
        return Gate::allows('domain_edit');
    }

    public function rules(): array
    {
        return [
            'domain' => [
                'string',
                'required',
            ],
            'tld' => [
                'string',
                'required',
            ],
            'registered_at' => [
                'required',
                'date_format:'.config('panel.date_format').' '.config('panel.time_format'),
            ],
            'expiration_date' => [
                'required',
                'date_format:'.config('panel.date_format').' '.config('panel.time_format'),
            ],
            'transfer_date' => [
                'required',
                'date_format:'.config('panel.date_format').' '.config('panel.time_format'),
            ],
            'who_is_privacy' => [
                'string',
                'nullable',
            ],
            'auth_code' => [
                'string',
                'nullable',
            ],
            'domain_pricing_id' => [
                'required',
                'integer',
            ],
            'user_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
