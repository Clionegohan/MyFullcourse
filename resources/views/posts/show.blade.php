<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>Menu</title>
        <!--都度適切な名前に変える必要あり？-->
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <a href="/categories/{{ $post->category->id }}">{{ $post->category->name }}</a>
        <h1 class="title">
            {{ $post->title }}
        </h1>
        <a class="">{{ $post->category->name }}</a>
        <div class="content">
            <div class="content__post">
                <h3>Menuの詳細</h3>
                <p>{{ $post->body }}</p>
            </div>
            @if($post->images->isNotEmpty())
            <div>
                @foreach($post->images as $image)
                <img src="{{ $image->image_url }}" alt="画像が読み込めません。">
                @endforeach
            </div>
            @endif
        </div>
        @if(auth()->user()->id === $post->user_id)
        <div class="edit"><a href="/posts/{{ $post->id }}/edit">edit</a></div>
        @endif
        <div class="footer">
            <a href="/">戻る</a>
        </div>
    </body>
</html>