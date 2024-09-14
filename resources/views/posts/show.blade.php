@extends('layouts.app')

@section('title', $post->title . 'の詳細-MyFullCourse')

@section('content')
    <div class="max-w-5xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto text-center">
        
        <!-- User -->
        <div class="flex items-center justify-center mb-4">
            <a href="/users/{{ $post->user->id }}" class="flex items-center text-[#810947]">
                @if ($post->user->profile_image === null)
                    <img class="w-12 h-12 rounded-full object-cover" src="https://res.cloudinary.com/dem5z47a6/image/upload/v1726220971/default_icon_odkziu.png" alt="プロフィール画像">
                @else
                    <img class="w-12 h-12 rounded-full object-cover" src="{{ $post->user->profile_image }}" alt="プロフィール画像">
                @endif
                <p class="ml-3 font-semibold text-center">{{ $post->user->name }}</p>
            </a>
        </div>        
        
        <a href="/categories/{{ $post->category->id }}" class="text-sm text-blue-600 hover:underline">{{ $post->category->name_jp }}</a>
        
        <h1 class="text-3xl font-bold mt-4 font-serif">{{ $post->title }}</h1>
        
        <!-- Body -->
        <div class="mt-6 bg-white p-6 rounded-lg shadow-lg">
            <div class="content__post">
                <p class="mb-4 text-lg text-gray-900 dark:text-white">料理の感想：</p>
                <p class="text-base text-gray-700 dark:text-gray-300 leading-relaxed text-left">{{ $post->body }}</p>
            </div>
            
            <!-- Image -->
            @if($post->images->isNotEmpty())
                <div class="grid {{ $post->images->count() > 1 ? 'grid-cols-2' : 'grid-cols-1'}} gap-4 mt-4 w-full">
                    @foreach($post->images as $image)
                        <img src="{{ $image->image_url }}" alt="画像が読み込めません。" class="max-w-sm h-auto rounded-lg shadow-md mx-auto">
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
            
            <!-- Map -->
            @if(!is_null($post->latitude) && !is_null($post->longitude))
                <div class="map mt-6">
                    <div id="map" class="rounded-lg shadow-md" style="height: 500px;"></div>
                </div>
            @endif
            @if(!is_null($post->address))
                <p class="mt-2 text-gray-800 dark:text-neutral-200">食べた場所：{{ $post->address }}</p>
            @endif
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
        
        <!-- Edit -->
        @if(auth()->user()->id === $post->user_id)
            <div class="mt-6">
                <a href="/posts/{{ $post->id }}/edit" class="text-blue-600 hover:underline">投稿の編集</a>
            </div>
        @endif
        
        <!-- Back -->
        <div class="footer mt-10">
            <a href="/" class="text-blue-600 hover:underline">戻る</a>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOMContentLoaded event fired');

            // 画像クリック時にモーダル表示
            function openModal(imageUrl) {
                document.getElementById('modalImage').src = imageUrl;
                document.getElementById('imageModal').classList.remove('hidden');
            }

            function closeModal() {
                document.getElementById('imageModal').classList.add('hidden');
            }

            document.querySelectorAll('.grid img').forEach(function(img) {
                img.addEventListener('click', function() {
                    openModal(this.src);
                });
            });

            document.getElementById('imageModal').addEventListener('click', closeModal);
            document.getElementById('modalImage').addEventListener('click', function(event) {
                event.stopPropagation();
            });
        });

        // Google Maps の初期化
        function initMap() {
            const latitude = @json($post->latitude);
            const longitude = @json($post->longitude);
            
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
@endsection
