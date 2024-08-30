@extends('layouts.app')

@section('title', 'みんなのMenu') <!-- ページごとのタイトルを設定 -->

@section('head')
    <!-- ページ固有のCSSやその他の<head>内容を追加 -->
    <style>
        button.delete-btn {
            background: none;
            border: none;
            color: black; /* 修正: 'brack'から'black'へ */
            cursor: pointer;
        }
    </style>
@endsection

@section('content')
    <a href="/posts/create">料理の共有</a>
    <h1 class="text-xs">みんなのMenu</h1>
    <div class='posts'>
        @foreach ($posts as $post)
            <div class='post'>
                <div class='profile'>
                    <a href="/users/{{ $post->user->id }}">{{ $post->user->name }}</a>
                    @if ($post->user->profile_image === null)
                        <img class="rounded-circle" src="{{ asset('storage/default.png') }}" alt="プロフィール画像" width="100" height="100">
                    @else
                        <img class="rounded-circle" src="{{ $post->user->profile_image }}" alt="プロフィール画像" width="100" height="100">
                    @endif
                </div>
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
        @endforeach
    </div>
    <div class='Paginate'>
        {{ $posts->links() }}
    </div>
@endsection

@section('scripts')
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
@endsection
