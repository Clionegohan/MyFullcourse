<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiaryRequest extends FormRequest
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
        if ($this->isMethod('post')) {
            $rules = [
                'diary.content' => 'required|string|max:200',
                'files' => 'nullable|array|max:4',
                'files.*' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
                ];
        }
        
        elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules = [
                'diary_content' => 'sometimes|string|max:200',
                'new_images' => 'nullable|array',
                'new_images.*' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
                'delete_images' => 'nullable|array',
                'delete_images.*' => 'exists:diaries_images,id',
                ];
        }
        
        return $rules;
        
    }
}
