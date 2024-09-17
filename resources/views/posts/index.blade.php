@extends('layouts.app')

@section('title', 'みんなのメニュー-MyFullCourse')

@section('content')
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
        <!-- Title -->
        <div class="max-w-2xl mx-auto text-center mb-10 lg:mb-14">
            <h2 class="text-2xl font-bold md:text-4xl md:leading-tight dark:text-white font-serif">みんなのフルコース</h2>
        </div>
        
        <!-- Grid -->
        <div class="grid grid-cols-1 gap-6 max-w-2xl mx-auto p-6">
            @foreach ($posts as $post)
                <!-- Card -->
                <div class="group flex flex-col focus:outline-none bg-white p-6 rounded-lg shadow-lg">
                    <!-- User -->
                    <div class="flex items-center mb-4">
                        <a href="/users/{{ $post->user->id }}" class="flex items-center text-[#810947]">
                            @if ($post->user->profile_image === null)
                                <img class="w-12 h-12 rounded-full object-cover" src="https://res.cloudinary.com/dem5z47a6/image/upload/v1726220971/default_icon_odkziu.png" alt="プロフィール画像">
                            @else
                                <img class="w-12 h-12 rounded-full object-cover" src="{{ $post->user->profile_image }}" alt="プロフィール画像">
                            @endif
                            <span class="ml-3 text-lg font-semibold">{{ $post->user->name }}</span>
                        </a>
                    </div>

                    <!-- Category -->
                    <a href="/categories/{{ $post->category->id }}" class="text-sm text-blue-600">{{ $post->category->name_jp }}</a>

                    <!-- Post Title -->
                    <h2 class="mt-2 text-xl font-semibold">
                        <a href="/posts/{{ $post->id }}"
                           class="text-gray-800 group-hover:text-gray-600 dark:text-neutral-300 dark:group-hover:text-white font-serif">
                            {{ $post->title }}
                        </a>
                    </h2>
                    
                    <!-- Images -->
                    @if($post->images->isNotEmpty())
                        <div class="mt-4 grid grid-cols-{{ min($post->images->count(), 2) }} gap-2">
                            @foreach ($post->images as $image)
                                <div class="relative">
                                    <img src="{{ $image->image_url }}" alt="画像が読み込めません。" 
                                         class="w-full h-full object-cover rounded-lg cursor-pointer" 
                                         onclick="openModal('{{ $image->image_url }}')"
                                    >
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Modal -->
                    <div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75 flex items-center justify-center">
                        <div class="relative">
                            <button onclick="closeModal()" class="absolute top-0 right-0 mt-2 mr-2 text-white text-3xl">&times;</button>
                            <img id="modalImage" src="" class="max-w-full max-h-screen object-contain">
                        </div>
                    </div>
                    
                    <!-- Body -->
                    <p class="mt-2 text-gray-800 dark:text-neutral-200">{{ $post->body }}</p>

                    <!-- Like Button -->
                    <div class="mt-4 flex items-center">
                        <button class="like-btn" data-post-id="{{ $post->id }}">
                            @if($post->isLikedByUser(auth()->user()))
                                <i class="fas fa-heart text-pink-500"></i>
                            @else
                                <i class="far fa-heart text-gray-400"></i>
                            @endif
                        </button>
                        <span class="ml-2 text-sm text-gray-600">{{ $post->likes()->count() }}</span>
                    </div>

                    <!-- Comments Section -->
                    @if($post->comments->isNotEmpty())
                        <div class="mt-4">
                            @foreach($post->comments as $comment)
                                <div class="relative bg-[#f7fafc] p-4 rounded-lg mb-2">
                                    <div class="flex items-center mb-4">
                                        <a href="/users/{{ $comment->user->id }}" class="flex items-center text-[#810947]">
                                            @if ($comment->user->profile_image === null)
                                                <img class="w-8 h-8 rounded-full object-cover" src="https://res.cloudinary.com/dem5z47a6/image/upload/v1726220971/default_icon_odkziu.png" alt="プロフィール画像">
                                            @else
                                                <img class="w-8 h-8 rounded-full object-cover" src="{{ $comment->user->profile_image }}" alt="プロフィール画像">
                                            @endif
                                            <span class="ml-3 text-lg font-semibold">{{ $comment->user->name }}</span>
                                        </a>
                                    </div>
                                    
                                    <p class="text-sm">{{ $comment->comment }}</p>

                                    @if (auth()->check() && auth()->user()->id === $comment->user_id)
                                        <form action="{{ route('comments.delete', $comment->id) }}" method="POST" onsubmit="return confirm('コメント削除します。よろしいですか？');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="absolute top-2 right-2 bg-none text-red-500 text-lg">x</button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Comment Form -->
                    <form action="{{ route('comments.store') }}" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="comment[post_id]" value="{{ $post->id }}">
                        <textarea name="comment[body]" rows="3" class="w-full p-2 border border-gray-300 rounded-lg" placeholder="コメントを入力してください。"></textarea>
                        <button type="submit" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-lg">コメントを投稿</button>
                    </form>
                </div>
                <!-- End Card -->
            @endforeach
        </div>
        <!-- End Grid -->
    </div>

    <!-- Pagination -->
    <div class='Paginate mt-10'>
        {{ $posts->links() }}
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOMContentLoaded event fired');

            // CSRFトークンをmetaタグから取得
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // いいねボタンのクリックイベント
            document.querySelectorAll('.like-btn').forEach(function(button) {
                console.log('Button found:', button);

                button.addEventListener('click', function() {
                    console.log('Like button clicked');

                    var postId = this.getAttribute('data-post-id');
                    var icon = this.querySelector('i');
                    var likeCountSpan = this.nextElementSibling;

                    console.log('postId:', postId);
                    console.log('icon:', icon);
                    console.log('likeCountSpan:', likeCountSpan);

                    // Fetch APIでいいねを送信
                    fetch(`/like/${postId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken // CSRFトークンを送信
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
                        } else {
                            console.error('Like request failed:', data);
                        }
                    })
                    .catch(error => console.error('Fetch error:', error));
                });
            });

            // 画像クリック時にモーダル表示
            function openModal(imageUrl) {
                document.getElementById('modalImage').src = imageUrl;
                document.getElementById('imageModal').classList.remove('hidden');
            }

            function closeModal() {
                document.getElementById('imageModal').classList.add('hidden');
            }

            document.querySelectorAll('.group img').forEach(function(img) {
                img.addEventListener('click', function() {
                    openModal(this.src);
                });
            });

            document.getElementById('imageModal').addEventListener('click', closeModal);
            document.getElementById('modalImage').addEventListener('click', function(event) {
                event.stopPropagation();
            });
        });
    </script>
@endsection
