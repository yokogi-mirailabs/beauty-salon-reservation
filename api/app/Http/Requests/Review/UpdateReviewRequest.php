<?php

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
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
            'good_flag' => ['required', 'boolean'],
            'evaluation' => ['required', 'integer'],
            'body' => ['required', 'string'],
        ];
    }

    public function attributes()
    {
        return [
            'evaluation' => '評価',
            'body' => 'レビュー内容',
        ];
    }
}
