<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectUpdateRequest extends FormRequest
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
            // 'id'=>'required|integer|exists:projects,id',
            'title' => 'nullable|string|max:500',
            'subtitle' => 'nullable|string|max:500',
            'date' => 'nullable|date',
            'brief'=>'nullable',
            'user_id'=>'nullable|integer|exists:users,id',
        ];
    }
}
