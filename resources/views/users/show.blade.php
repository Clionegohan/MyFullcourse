@extends('layouts.app')

@section('title', $user->name . 'のフルコースメニュー-MyFullCourse')

@section('head')
    <!-- ページ固有のCSSやその他の<head>内容を追加 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    
        .full-course-container {
            background-image: url('{{ asset('storage/MyFullCourse_background.png') }}');
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            background-size: cover;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            max-width: 800px;
            text-align: center;
            z-index: 1;
            padding:70px;
            margin: 30px auto;
        }

        .categories {
            position: relative;
            z-index: 1; 
            color: #333;
            padding:30px;
        }

    </style>
@endsection

@section('content')
    <div class="mx-auto px-4 py-10">
        <!-- プロフィール -->
        <div class='profile text-center mb-10'>
            <h3 class="text-3xl font-semibold">{{ $user->name }}</h3>
            @if ($user->profile_image === null)
                <img class="w-24 h-24 rounded-full object-cover mx-auto" src="{{ asset('storage/default.png') }}" alt="プロフィール画像">
            @else
                <img class="w-24 h-24 rounded-full object-cover mx-auto" src="{{ $user->profile_image }}" alt="プロフィール画像">
            @endif
            @if(auth()->user()->id === $user->id)
                <div class="mt-4">
                    <a href="/users/{{ $user->id }}/edit" class="text-blue-500 hover:underline">プロフィールを編集</a>
                </div>
            @endif
        </div>

        <!-- 全体を囲む枠 -->
        <div class="full-course-background">
            <div class="full-course-container">
                <h1 class="text-3xl font-bold text-center" style="font-family: 'Noto Serif JP', serif; margin-top: 20px;">{{ $user->name }}のフルコースメニュー</h1>
                <div class='categories flex flex-col items-center'>
                    @foreach ($categories as $category)
                        <div class='category mb-6'>
                            <h2 class="text-xl font-semibold mb-4" style="font-family: 'Noto Serif JP', serif; margin-top: 20px;">{{ $category->name_jp }}</h2>
                                @if ($post = $posts->get($category->id))
                                    <div class='post'>
                                        <h3 class='title text-lg font-medium'>
                                            <a href="/posts/{{ $post->id }}" class="text-xl hover:underline font-semibold mb-2" style="font-family: 'Noto Serif JP', serif; margin-top: 20px;">{{ $post->title }}</a>
                                        </h3>
                                    </div>
                                @else
                                    @if (auth()->check() && auth()->user()->id === $user->id)
                                        <a href="/posts/create" 
                                           class="border-2 border-gray-300 bg-white text-center leading-10 text-gray-500 hover:border-gray-400 mx-auto"
                                           style="display: block; width: 200px !important; height: 50px;">
                                        </a>
                                    @else
                                        <div class="border-2 border-gray-300 bg-white text-center leading-10 text-gray-500 hover:border-gray-400"
                                             style="display: block; width: 200px !important; height: 50px;">
                                        </div>
                                    @endif
                                @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 戻るボタン -->
        <div class="mt-10 text-center">
            <a href="/" class="text-blue-500 hover:underline">戻る</a>
        </div>
    </div>
    @php
        $hideSearchBox = true;
    @endphp
@endsection

