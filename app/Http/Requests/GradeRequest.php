<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GradeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required'],
            'ratio_refund' => ['required', 'numeric'],
            'min_price' => ['required', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
