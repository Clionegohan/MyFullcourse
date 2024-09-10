@extends('layouts.app')

@section('title', '投稿編集画面-MyFullCourse')

@section('head')
    <style>
        .image-container {
            display: inline-block;
            margin-right: 10px;
            position: relative;
        }
        .image-container img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }
        .image-container input[type="checkbox"] {
            position: absolute;
            top: 5px;
            right: 5px;
            transform: scale(1.5);
        }
        .image_error {
            color: red;
        }
    </style>
@endsection

@section('content')
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto text-center">
        <h1 class="text-2xl font-bold md:text-4xl md:leading-tight dark:text-white" style="font-family: 'Noto Serif JP', serif;">編集画面</h1>
        
        <form action="/posts/{{ $post->id }}" method="POST" enctype="multipart/form-data" class="mt-10 max-w-lg mx-auto text-left">
            @csrf
            @method('PUT')
            
            <div class='content__title mb-6'>
                <label for="title" class="block mb-2 text-sm font-medium text-gray-900">料理名</label>
                <input type='text' name='post[title]' id="title" value="{{ $post->title }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"/>
            </div>
            
            <div class='content__body mb-6'>
                <label for="body" class="block mb-2 text-sm font-medium text-gray-900">味の感想や想い出</label>
                <textarea name='post[body]' id="body" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('post.body', $post->body) }}</textarea>
            </div>
            
            <div class="current-images mb-6">
                <label class="block mb-2 text-sm font-medium text-gray-900">現在の画像</label>
                @if($post->images->isNotEmpty())
                    <div class="flex">
                        @foreach($post->images as $image)
                            <div class="image-container">
                                <img src="{{ $image->image_url }}" alt="画像が読み込めません。">
                                <input type="checkbox" name="delete_images[]" value="{{ $image->id }}"> 削除
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>現在の画像はありません。</p>
                @endif
            </div>

            <div class="new-images mb-6">
                <label for="new_images" class="block mb-2 text-sm font-medium text-gray-900">新しい画像を追加</label>
                <input type="file" name="new_images[]" id="new_images" multiple class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <p class="image_error">{{ $errors->first('new_images') }}</p>
            </div>
            
            <input type="submit" value="保存" class="bg-blue-500 text-white rounded-lg px-4 py-2 cursor-pointer hover:bg-blue-700"/>
        </form>
        
        <form action="/posts/{{ $post->id }}" id="form_{{ $post->id }}" method="post" class="mt-6">
            @csrf
            @method('DELETE')
            <button type="button" onclick="deletePost({{ $post->id }})" class="bg-red-500 text-white rounded-lg px-4 py-2 cursor-pointer hover:bg-red-700">削除</button>
        </form>

        <div class="footer mt-6">
            <a href="/" class="text-blue-500 hover:underline">戻る</a>
        </div>
    </div>
    @php
        $hideSearchBox = true;
    @endphp
@endsection

@section('scripts')
    <script>
        function deletePost(id) {
            'use strict';
            
            if (confirm('削除すると復元できません。\n本当に削除しますか？')) {
                document.getElementById(`form_${id}`).submit();
            }
        }
    </script>
@endsection
