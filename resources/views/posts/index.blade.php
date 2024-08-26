<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>MyFullCourse</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </head>
    <body>
        <a href="/posts/create">料理の共有</a>
        <h1>みんなのMenu</h1>
        <div class='posts'>
            @foreach ($posts as $post)
                <div class='post'>
                    <h2 class="user">
                        <a href="/users/{{ $post->user->id }}">{{ $post->user->name }}</a>
                    </h2>
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
                <div class='like'>
                <button class="like-btn" data-post-id="{{ $post->id }}">
                    @if($post->isLikedByUser(auth()->user()))
                        <i class="fas fa-heart text-pink-500"></i>
                    @else
                        <i class="far fa-heart text-gray-400"></i>
                    @endif
                </button>
                <span class="like-count">{{ $post->likes_count }}</span>
                </div>
            @endforeach
        </div>
        <div class='Paginate'>
            {{ $posts->links() }}
        </div>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.like-btn').forEach(function(button) {
                    button.addEventListener('click', function() {
                    
                        console.log('Like button clicked');
                        
                        var postId = this.getAttribute('data-post-id');
                        var icon = this.querySelector('i');
                        var likeCountSpan = this.nextElementSibling;
                        
                        fetch('/like/' + postId, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({})
                        })
                        .then(response => response.json())
                        .then(data => {
                        
                            console.log('Server response:', data);
                        
                            if (data.success) {
                                // アイコンのクラスを切り替えて「いいね」の状態を更新
                                icon.classList.toggle('fas');
                                icon.classList.toggle('far');
                                icon.classList.toggle('text-gray-400');
                                icon.classList.toggle('text-pink-500');
                                
                                // いいね数を更新
                                likeCountSpan.textContent = data.like_count;
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    });
                });
            });
        </script>

    </body>
</html>