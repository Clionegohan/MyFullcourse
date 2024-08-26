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
                @if($categories->isEmpty())
                    <p>カテゴリがありません。</p>
                @else
                    <select name="post[category_id]">
                        <option value="" disabled selected>カテゴリを選択してください。</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                @endif
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
                <input type="file" name="files[]" multiple> 
                <p class="image_error" style="color:red">{{ $errors->first('files') }}</p>
            </div>
            <div class="map">
                <h2>場所を選択または入力して下さい。</h2>
                <input type="text" id="address" name="post[address]" placeholder="住所を入力" onblur="geocoderAddress()">
                <input type="hidden" id="latitude" name="post[latitude]">
                <input type="hidden" id="longitude" name="post[longitude]">
            </div>
            <input type="submit" value="投稿"/>
        </form>
        <div class="footer">
            <a href="/">戻る</a>
        </div>
        <script>
            function geocoderAddress() {
                const address = document.getElementById('address').value;
                const geocoder = new google.maps.Geocoder();
                
                geocoder.geocode({ 'address':address }, function(results, status) {
                    if (status === 'OK') {
                    const latitude = results[0].geometry.location.lat();
                    const longitude = results[0].geometry.location.lng();
                    
                    console.log("Latitude: " + latitude);
                    console.log("Longitude: " + longitude);
                    
                    document.getElementById('latitude').value = latitude;
                    document.getElementById('longitude').value = longitude;
                } else {
                    alert('Geocode was not successful for the following reason ' + status);
                    }
                });
            }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key=AIzaSyD8p5NaW01uEm6AJVimJ75gyPwvV8obiTY" async defer></script>        
    </body>
</html>