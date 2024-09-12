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
        $post = $this->route('post');

        // 新規投稿、既存の画像がない場合このバリデーションをスキップ
        if (!$post || (!$this->hasFile('new_images') && !$this->filled('delete_images'))) {
            return;
        }

        $validator->after(function ($validator) use ($post) {
            $existingImagesCount = $post->images()->count();

            $deletedImagesCount = is_array($this->input('delete_images')) ? count($this->input('delete_images')) : 0;

            $newImagesCount = count($this->file('new_images', []));

            $remainingImagesCount = $existingImagesCount - $deletedImagesCount + $newImagesCount;

            if ($remainingImagesCount > 4) {
                $validator->errors()->add('new_images', '投稿に含めることができる画像は合計4枚までです。');
            }
        });
    }
    
    public function rules(): array
    {

        if ($this->isMethod('post')) {
            // 新規投稿用のルール
            $rules = [
                'post.title' => 'required|string|max:100',
                'post.body' => 'required|string|max:400',
                'post.category_id' => 'required|exists:categories,id',
                'files.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'files' => 'nullable|array|max:4',
                'post.address' => 'nullable|string|max:255',
                'post.latitude' => 'nullable|numeric|between:-90,90',
                'post.longitude' => 'nullable|numeric|between:-180,180',
            ];
            
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // 更新用のルール
            $rules = [
                'post.title' => 'sometimes|string|max:100',
                'post.body' => 'sometimes|string|max:400',
                'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'new_images' => 'nullable|array',
                'delete_images' => 'nullable|array',
                'delete_images.*' => 'exists:images,id',
            ];
        }

        return $rules;
    }
}
