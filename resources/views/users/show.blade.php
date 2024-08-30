<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MyFullCourse</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body>
        <div class='profile'>
            <h3>{{ $user->name }}</h3>
            @if ($user->profile_image ===null)
                <img class="rounded-circle" src="{{ asset('storage/default.png') }}" alt="プロフィール画像" width="100" height="100">
            @else
                <img class="rounded-circle" src="{{ $user->profile_image }}" alt="プロフィール画像" width="100" height="100">
            @endif
            @if(auth()->user()->id === $user->id)
                <div class="users.edit">
                    <a href="/users/{{ $user->id }}/edit">edit</a>
                </div>
            @endif
        </div>
        
        <h1>{{ $user->name }} フルコースメニュー</h1>
        <div class='categories'>
            @foreach ($categories as $category)
                <div class='category'>
                    <h2>{{ $category->name_jp }}</h2>
                    @if ($post = $posts->get($category->id))
                        <div class='post'>
                            <h3 class='title'>
                                <a href="/posts/{{ $post->id }}">{{ $post->title }}</a>
                            </h3>
                            <p class='body'>{{ $post->body }}</p>
                            @if($post->images->isNotEmpty())
                                <div class='image'>
                                    @foreach($post->images as $image)
                                    <img src="{{ $image->image_url }}" alt="画像が読み込めません。">
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <a href="/posts/create" class="block w-72 h-12 border-2 border-black bg-white mb-2"></a>
                    @endif
                </div>
            @endforeach
        </div>
        
        <div class="footer">
            <a href="/">戻る</a>
        </div>
    </body>
</html>
