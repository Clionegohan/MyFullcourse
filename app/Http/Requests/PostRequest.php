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
     
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $post = $this->route('post'); // 現在の投稿を取得
            
            if ($this->hasFile('new_images') || $this->filled('delete_images')) {
            // 既存の画像の枚数を取得
                $existingImagesCount = $post->images()->count();

            // 削除する画像の数を取得
                $deletedImagesCount = is_array($this->input('delete_images')) ? count($this->input('delete_images')) : 0;

            // 新しくアップロードされる画像の数を取得
                $newImagesCount = count($this->file('new_images', []));

            // 残る予定の画像の数を計算
                $remainingImagesCount = $existingImagesCount - $deletedImagesCount + $newImagesCount;

                if ($remainingImagesCount > 4) {
                $validator->errors()->add('new_images', '投稿に含めることができる画像は合計4枚までです。');
                }
            }
        });
    }
     
    public function rules(): array
    {
        $rules = [
            'post.title' => 'sometimes|required|string|max:100',
            'post.body' => 'sometimes|required|string|max:400',
            'files.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'files' => 'nullable|array|max:4',

            'post.address' => 'nullable|string|max:255',
            'post.latitude' => 'nullable|numeric|between:-90,90',
            'post.longitude' => 'nullable|numeric|between:-180,180',
        ];
        
        if ($this->isMethod('post')) {
            $rules['post.category_id'] = 'required|exists:categories,id';
        }
        if ($this->isMethod('put')) {
            $rules = [
                'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'new_images' => 'nullable|array',
                'delete_images' => 'nullable|array',
                'delete_images.*' => 'exists:images,id',                
            ];
        }
        
        return $rules;
    }
}
