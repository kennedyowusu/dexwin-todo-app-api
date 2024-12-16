<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;

class TodoRequest extends FormRequest
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
            'title' => 'required|string',
            'details' => 'string|nullable',
            'status' => 'required|in:completed,in progress,not started',
            'user_id' => 'required|exists:users,id',
        ];
    }
}
