<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlarmRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'exists:users'],
            'type' => ['required'],
            'contact' => ['nullable'],
            'email' => ['nullable', 'email', 'max:254'],
            'preset_product_id' => ['nullable', 'exists:preset_product'],
            'order_id' => ['nullable', 'exists:orders'],
            'preset_id' => ['nullable', 'exists:presets'],
            'qna_id' => ['nullable', 'exists:qnas'],
            'prototype_id' => ['nullable', 'exists:prototypes'],
            'feedback_id' => ['nullable', 'exists:feedback'],
            'estimate_id' => ['nullable', 'exists:estimates'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
