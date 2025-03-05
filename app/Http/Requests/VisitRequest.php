<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VisitRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'exists:users'],
            'ip' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
