<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>MyFullCourse</title>
    </head>
    <body>
        <h1 class="title">編集画面</h1>
        <div class="content">
            <form action="/posts/{{ $post->id }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class='content__title'>
                    <h2>料理名</h2>
                        <input type='text' name='post[title]' value="{{ $post->title }}">
                </div>
                <div class='content__body'>
                    <h2>味の感想や想い出</h2>
                        <input type='text' name='post[body]' value="{{ $post->body }}">
                    </div>
                
                <div class="current-images">
                    @if($post->images->isNotEmpty())
                        <div class="flex">
                            @foreach($post->images as $image)
                                <div class="image-container">
                                    <img src="{{ $image->image_url }}" alt="画像が読み込めません。" class="w-32 h-32 object-cover mb-2">
                                    <input type="checkbox" name="delete_images[]" value="{{ $image->id }}"> この画像を削除
                                </div>
                            @endforeach
                        </div>
                      @endif
                </div>

                <div class="new-images">
                    <h3>新しい画像を追加</h3>
                    <input type="file" name="new_images[]" multiple>
                </div>
                
                <input type="submit" value="保存">
            </form>
            
            <form action="/posts/{{ $post->id }}" id="form_{{ $post->id }}" method="post">
                @csrf
                @method('DELETE')
                <button type="button" onclick="deletePost({{ $post->id }})">削除</button>
            </form>
        </div>
        
        <div class="footer">
            <a href="/">戻る</a>
        </div>
        
        <script>
            function deletePost(id) {
                'use strict'
                
                if (confirm('削除すると復元できません。\n本当に削除しますか？')) {
                    document.getElementById(`form_${id}`).submit();
                }
            }
        </script>
    </body>
</html>
