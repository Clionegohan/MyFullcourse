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
        <div id="map" style="height:500px"></div>
        </div>
        @if(auth()->user()->id === $post->user_id)
        <div class="edit"><a href="/posts/{{ $post->id }}/edit">edit</div>
        @endif
        <div class="footer">
            <a href="/">戻る</a>
        </div>

        <script>
            function initMap() {
                const latitude = {{ $post->latitude }};
                const longitude = {{ $post->longitude }};
                
                const map = new google.maps.Map(document.getElementById('map'), {
                    center: { lat: latitude, lng: longitude },
                    zoom: 15
                });
                
                const marker = new google.maps.Marker({
                    position: { lat: latitude, lng: longitude },
                    map: map
                });
            }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key=AIzaSyD8p5NaW01uEm6AJVimJ75gyPwvV8obiTY&callback=initMap" async defer></script>
    </body>
</html>