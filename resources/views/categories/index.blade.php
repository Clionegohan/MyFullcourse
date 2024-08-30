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
                    <a href="/categories/{{ $post->category->id }}">{{ $post->category->name_jp }}</a>
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

            @if($post->comments->isNotEmpty())
                <div class="comment">
                    @foreach($post->comments as $comment)
                        <p>{{ $comment->user->name }}</p>
                        <p>{{ $comment->comment }}</p>

                        @if (auth()->check() && auth()->user()->id === $comment->user_id)
                            <form action="{{ route('comments.delete', $comment->id) }}" method="POST" onsubmit="return confirm('コメント削除します。よろしいですか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn">x</button>
                            </form>
                        @endif
                    @endforeach
                </div>
            @endif
            <form action="{{ route('comments.store') }}" method="POST">
                @csrf
                <input type="hidden" name="comment[post_id]" value="{{ $post->id }}">
                <textarea name="comment[body]" rows="3" placeholder="コメントを入力してください。"></textarea>
                <button type="submit">コメントを投稿</button>
            </form>
    </div>
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