<?php

namespace App\Http\Requests\Admin;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('user_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'email' => [
                'required',
                'unique:users,email,'.request()->route('user')->id,
            ],
            'email_verified_at' => [
                'date',
                'nullable',
            ],
            'password' => [
                'string',
                'nullable',
            ],
            'roles.*' => [
                'integer',
            ],
            'roles' => [
                'required',
                'array',
            ],
            'phone' => [
                'string',
                'nullable',
                'min:10',
                'max:13',
                'unique:users,phone,'.request()->route('user')->id,
            ],
        ];
    }
}
