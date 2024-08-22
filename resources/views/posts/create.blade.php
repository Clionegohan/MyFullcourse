<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>MyFullCourse</title>
    </head>
    <body>
        <h1>Menu</h1>
        
        <form action="/posts" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="category">
                <h2>Category</h2>
                <select name="post[category_id]">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
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
            <div class="image">
                <div id="image_fields">
                    <input type="file" name="image[]" class="image-input" onchange="addImageField(this)">                    
                </div>
                <p class="image_error" style="color:red">{{ $errors->first('image') }}</p>
            </div>
            <input type="submit" value="投稿"/>
        </form>
        <div class="back">[<a href="/">戻る</a>]</div>
        <script>
            function addImageField(input) {
                var imageFields = document.querySelectorAll('.image-input').length;
                
                if (imageFields < 4) {
                    var newField = document.createElement('input');
                    newField.type = 'file';
                    newField.name = 'image[]';
                    newField.className = 'image-input';
                    newField.onchange = function() {
                        addImageField(this);
                    };
                }
                document.getElementById('image_fields').appendchild(newField);
            }
        </script>
    </body>
</html>