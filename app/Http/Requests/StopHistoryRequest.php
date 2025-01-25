<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StopHistoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'package_id' => ['required', 'exists:packages'],
            'memo' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
