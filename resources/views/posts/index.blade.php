<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MyFullCourse</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <a href="/posts/create">料理の共有</a>
        <h1>Menu</h1>
        <div class='posts'>
            @foreach ($posts as $post)
                <div class='post'>
                    <a href="/categories/{{ $post->category->id }}">{{ $post->category->name }}</a>
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
        <div class='Paginate'>
            {{ $posts->links() }}
        </div>
    </body>
</html>