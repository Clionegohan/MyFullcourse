<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
        $rules = [
            'post.title' => 'sometimes|required|string|max:100',
            'post.body' => 'sometimes|required|string|max:400',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image' => 'nullable|array|max:4',
        ];
        
        if ($this->isMethod('post')) {
            $rules['post.category_id'] = 'required|exists:categories,id';
        }
        
        return $rules;
    }
}
