<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MyFullCourse</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </head>
    <body>
        <h1>{{ $user->name }}のフルコース</h1>
        <div class='posts'>
            @foreach ($posts as $post)
                <div class='post'>
                    <p>{{ $post->category->name }}</p>
                    <h2 class='title'>
                        <a href="/posts/{{ $post->id }}">{{ $post->title }}</a>
                    </h2>
                    <p class='body'>{{ $post->body }}</p>
                    @if($post->images->isNotEmpty())
                    <div class='image'>
                        @foreach($post->images as $image)
                        <img src="{{ $image->image_url }}" alt="画像が読み込めません。">
                        @endforeach
                    </div>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="footer">
            <a href="/">戻る</a>
        </div>
    </body>
</html>