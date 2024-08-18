<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>MyFullCourse</title>
    </head>
    <body>
        <h1>Menu</h1>
        <!--カテゴリ名を入れたげるといいのかも-->
        <form action="/posts" method="POST">
            @csrf
            <div class="title">
                <h2>Menu</h2>
                <input type="text" name="post[title]" placeholder="料理名" value="{{ old('post.title') }}"/>
                <p class="title_error" style="color:red">{{ $errors->first('post.title') }}</p>
            </div>
            <div class="body">
                <h2>あなたの思い出の料理</h2>
                <textarea name="post[body]" placeholder="味の感想、料理の思い出をみんなと共有しよう！">{{ old('post.body') }}</textarea>
                <p class="body_error" style="color:red">{{ $errors->first('post.body') }}</p>
            </div>
            <input type="submit" value="投稿"/>
        </form>
        <div class="back">[<a href="/">戻る</a>]</div>
    </body>
</html>