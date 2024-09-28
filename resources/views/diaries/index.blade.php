@extends('layouts.app')

@section('title', 'グルメダイアリー-MyFullCourse')

@section('content')
<!-- メインコンテンツ全体 -->
<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <!-- Title -->
    <div class="max-w-2xl mx-auto text-center mb-10 lg:mb-14">
        <h1 class="text-2xl font-bold md:text-4xl md:leading-tight dark:text-white font-serif">グルメダイアリー</h1>
    </div>
    
    <!-- コンテンツ全体を2カラムレイアウトに -->
    <div class="lg:flex lg:space-x-6">
        <!-- サイドバー（注目のフルコース） -->
        <div class="lg:w-1/4 bg-white shadow-md p-4 max-w-sm h-full text-center overflow-y-auto">
            <h2 class="text-lg font-semibold mb-4" style="font-family: 'Noto Serif JP', serif;">注目の投稿</h2>
            @foreach ($headerCategories as $category)
                @php
                    $topPost = $category->posts()->withCount('likes')->orderBy('likes_count', 'desc')->first();
                @endphp
                
                @if ($topPost)
                    <div class="top-post mb-4">
                        <h3 class="font-bold text-sm" style="font-family: 'Noto Serif JP', serif;">{{ $category->name_jp }}</h3>
                        <a href="{{ route('posts.show', $topPost->id) }}" class="block">
                            <p class="text-sm" style="font-family: 'Noto Serif JP', serif;">{{ $topPost->title }}</p>
                            <span class="text-xs text-gray-600">
                                {{ $topPost->user->name }} いいね: {{ $topPost->likes_count }}
                            </span>
                        </a>
                    </div>
                @else
                    <p class="text-sm text-gray-600">{{ $category->name_jp }}にはまだ投稿がありません。</p>
                @endif
            @endforeach
        </div>
        
        <!-- 投稿カードのグリッド部分 -->
        <div class="lg:w-3/4 grid grid-cols-1 gap-6 max-w-2xl p-6">
            @foreach ($diaries as $diary)
                <!-- Card -->
                <div class="group flex flex-col bg-white p-6 rounded-lg shadow-lg">
                    <!-- User -->
                    <div class="flex items-center mb-4">
                        <a href="/users/{{ $diary->user->id }}" class="flex items-center text-[#810947]">
                            @if ($diary->user->profile_image === null)
                                <img class="w-12 h-12 rounded-full object-cover" src="https://res.cloudinary.com/dem5z47a6/image/upload/v1726220971/default_icon_odkziu.png" alt="プロフィール画像">
                            @else
                                <img class="w-12 h-12 rounded-full object-cover" src="{{ $diary->user->profile_image }}" alt="プロフィール画像">
                            @endif
                            <span class="ml-3 text-lg font-semibold">{{ $diary->user->name }}</span>
                        </a>
                    </div>
                    
                    <!-- Content -->
                    <a href="/diaries/{{ $diary->id }}" class="mt-2 text-gray-800">
                        {{ $diary->content }}
                    </a>
                    
                    <!-- Images -->
                    @if($diary->images->isNotEmpty())
                        <div class="mt-4 grid grid-cols-{{ min($diary->images->count(), 2) }} gap-2">
                            @foreach ($diary->images as $image)
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
                    
                    <!-- Like Button -->
                    <div class="mt-4 flex items-center">
                        <button class="like-btn" data-diary-id="{{ $diary->id }}">
                            @if($diary->isLikedByUser(auth()->user()))
                                <i class="fas fa-heart text-pink-500"></i>
                            @else
                                <i class="far fa-heart text-gray-400"></i>
                            @endif
                        </button>
                        <span class="ml-2 text-sm text-gray-600">{{ $diary->likes()->count() }}</span>
                    </div>

                    <!-- Comments Section -->
                    @if($diary->comments->isNotEmpty())
                        <div class="mt-4">
                            @foreach($diary->comments as $comment)
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
                    <form action="{{ route('diary.comments.store', ['diary' => $diary->id]) }}" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="comment[diary_id]" value="{{ $diary->id }}">
                        <textarea name="comment[body]" rows="3" class="w-full p-2 border border-gray-300 rounded-lg" placeholder="コメントを入力してください。"></textarea>
                        <button type="submit" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-lg">コメントを投稿</button>
                    </form>
                </div>
                <!-- End Card -->
            @endforeach
        </div>
    </div>
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

                    var diaryId = this.getAttribute('data-diary-id');
                    var icon = this.querySelector('i');
                    var likeCountSpan = this.nextElementSibling;

                    console.log('diaryId:', diaryId);
                    console.log('icon:', icon);
                    console.log('likeCountSpan:', likeCountSpan);

                    // Fetch APIでいいねを送信
                    fetch(`/diaries/like/${diaryId}`, {
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
