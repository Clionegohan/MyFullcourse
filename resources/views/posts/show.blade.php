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
        <h1 class="title">
            {{ post->title }}
        </h1>
        <div class="content">
                <div class="content_post">
                    <h3>Menuの詳細</h3>
                    <p>{{ $post->body }}</p>
                </div>
        </div>
        <div class="footer">
            <a href="/">戻る</a>
        </div>
    </body>
</html>