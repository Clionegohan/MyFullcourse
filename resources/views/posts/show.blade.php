@extends('layouts.app')

@section('title', $post->title . 'の詳細 - MyFullCourse')

@section('content')
    <div class="max-w-6xl px-4 py-12 mx-auto flex flex-col items-center text-center">
        
        <!-- User Section -->
        <div class="flex items-center justify-center mb-6">
            <a href="/users/{{ $post->user->id }}" class="flex items-center text-[#810947]">
                @if ($post->user->profile_image === null)
                    <img class="w-16 h-16 rounded-full object-cover" src="https://res.cloudinary.com/dem5z47a6/image/upload/v1726220971/default_icon_odkziu.png" alt="プロフィール画像">
                @else
                    <img class="w-16 h-16 rounded-full object-cover" src="{{ $post->user->profile_image }}" alt="プロフィール画像">
                @endif
                <p class="ml-4 text-xl font-semibold">{{ $post->user->name }}</p>
            </a>
        </div>        
        
        <!-- Category -->
        <a href="/categories/{{ $post->category->id }}" class="text-sm text-blue-600 hover:underline">{{ $post->category->name_jp }}</a>
        
        <!-- Title -->
        <h1 class="text-4xl font-bold mt-4 font-serif">{{ $post->title }}</h1>
        
        <!-- Content Section -->
        <div class="mt-10 bg-white p-8 rounded-lg shadow-lg max-w-2xl w-full">
            <div class="content__post text-left">
                <p class="mb-4 text-xl text-gray-900">料理の感想：</p>
                <p class="text-base text-gray-700 leading-relaxed">{{ $post->body }}</p>
            </div>
            
            <!-- Images -->
            @if($post->images->isNotEmpty())
                <div class="grid {{ $post->images->count() > 1 ? 'grid-cols-2' : 'grid-cols-1'}} gap-4 mt-8 w-full">
                    @foreach($post->images as $image)
                        <img src="{{ $image->image_url }}" alt="画像が読み込めません。" class="w-full h-auto rounded-lg shadow-md cursor-pointer" onclick="openModal('{{ $image->image_url }}')">
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
            
            <!-- Map Section -->
            @if(!is_null($post->latitude) && !is_null($post->longitude))
                <div class="map mt-10">
                    <div id="map" class="rounded-lg shadow-md" style="height: 400px;"></div>
                </div>
            @endif
            @if(!is_null($post->address))
                <p class="mt-4 text-gray-800 dark:text-neutral-200">食べた場所：{{ $post->address }}</p>
            @endif
        </div>

        <!-- Comments Section -->
        @if($post->comments->isNotEmpty())
            <div class="mt-8 bg-white p-6 rounded-lg shadow-lg max-w-2xl w-full text-left">
                <h2 class="text-lg font-bold mb-4">コメント</h2>
                @foreach($post->comments as $comment)
                    <div class="relative bg-[#f7fafc] p-4 rounded-lg mb-4">
                        <div class="flex items-center mb-2">
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

        <!-- Edit and Back Links -->
        <div class="mt-10 max-w-2xl w-full text-left">
            @if(auth()->user()->id === $post->user_id)
                <a href="/posts/{{ $post->id }}/edit" class="text-blue-600 hover:underline">投稿の編集</a>
            @endif

            <div class="mt-6">
                <a href="javascript:void(0);" onclick="window.history.back();" class="text-blue-500 hover:underline">戻る</a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOMContentLoaded event fired');

            // 画像クリック時にモーダル表示
            window.openModal = function(imageUrl) {
                document.getElementById('modalImage').src = imageUrl;
                document.getElementById('imageModal').classList.remove('hidden');
            }

            window.closeModal = function() {
                document.getElementById('imageModal').classList.add('hidden');
            }
        });

        // Google Maps の初期化
        function initMap() {
            const latitude = parseFloat(@json($post->latitude));
            const longitude = parseFloat(@json($post->longitude));
            
            if (isNaN(latitude) || isNaN(longitude)) {
                console.error('Latitude or Longitude is not a number.');
                return;
            }
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
    <script src="https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap" async defer></script>
@endsection
