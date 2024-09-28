@extends('layouts.app')

@section('title', 'ダイアリー投稿-MyFullCourse')

@section('content')
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto text-center">
        <h1 class="text-2xl font-bold md:text-4xl md:leading-tight dark:text-white font-serif">グルメダイアリー</h1>
        <form action="/diaries" method="POST" enctype="multipart/form-data" class="mt-10 max-w-lg mx-auto text-left">
            @csrf
            
            <div class="content mb-6">
                <label for="content" class="block mb-2 text-sm font-medium text-gray-900">
                    グルメダイアリー <span class="text-red-500">*</span>
                </label>
                <textarea name="diary[content]" id="content" placeholder="フルコースとまではいかない、あなたのグルメに関する話を共有しましょう！" 
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('post.body') }}</textarea>
                <p class="text-red-500">{{ $errors->first('diary.content') }}</p>
            </div>
            
            <div class="image mb-6">
                <label for="files" class="block mb-2 text-sm font-medium text-gray-900">画像</label>
                <input type="file" name="files[]" id="files" multiple
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"> 
                <p class="text-red-500">{{ $errors->first('files') }}</p>
            </div>
            
            <input type="submit" value="投稿" class="bg-blue-500 text-white rounded-lg px-4 py-2 cursor-pointer hover:bg-blue-700"/>
        </form>
        
        <div class="footer mt-6">
            <a href="/" class="text-blue-500 hover:underline">戻る</a>
        </div>
    </div>
    @php
        $hideSearchBox = true;
    @endphp
@endsection