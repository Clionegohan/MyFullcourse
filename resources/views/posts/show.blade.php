@extends('layouts.app')

@section('title', $post->title . 'の詳細-MyFullCourse')

@section('content')
    <div class="max-w-5xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto text-center">
        
        <div class="flex items-center justify-center mb-4">
            <a href="/users/{{ $post->user->id }}" class="flex items-center" style="color: #810947;">
                @if ($post->user->profile_image === null)
                    <img class="w-12 h-12 rounded-full object-cover" src="{{ asset('storage/default.png') }}" alt="プロフィール画像">
                @else
                    <img class="w-12 h-12 rounded-full object-cover" src="{{ $post->user->profile_image }}" alt="プロフィール画像">
                @endif
            <p class="ml-3 font-semibold text-center">{{ $post->user->name }}</p>
            </a>
        </div>        
        
        <!-- カテゴリリンク -->
        <a href="/categories/{{ $post->category->id }}" class="text-sm text-blue-600 hover:underline">{{ $post->category->name_jp }}</a>
        
        <!-- タイトル -->
        <h1 class="text-3xl font-bold mt-4" style="font-family: 'Noto Serif JP', serif;">{{ $post->title }}</h1>
        
        <!-- 投稿の詳細内容 -->
        <div class="mt-6 bg-white p-6 rounded-lg shadow-lg">
            <div class="content__post">
                <p class="mb-4 text-lg text-gray-900 dark:text-white">料理の感想：</p>
                <p class="text-base text-gray-700 dark:text-gray-300 leading-relaxed text-left">{{ $post->body }}</p>
            </div>
            
            <!-- 画像表示 -->
            @if($post->images->isNotEmpty())
                <div class="grid {{ $post->images->count() > 1 ? 'grid-cols-2' : 'grid-cols-1'}} gap-4 mt-4 w-full">
                    @foreach($post->images as $image)
                        <img src="{{ $image->image_url }}" alt="画像が読み込めません。" class="max-w-sm h-auto rounded-lg shadow-md mx-auto">
                    @endforeach
                </div>
            @endif
            <!-- モーダル -->
            <div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75 flex items-center justify-center">
                <div class="relative">
                    <button onclick="closeModal()" class="absolute top-0 right-0 mt-2 mr-2 text-white text-3xl">&times;</button>
                    <img id="modalImage" src="" class="max-w-full max-h-screen object-contain">
                </div>
            </div>
            
            <!-- 地図表示 -->
            @if(!is_null($post->latitude) && !is_null($post->longitude))
                <div class="map mt-6">
                    <div id="map" style="height:500px" class="rounded-lg shadow-md"></div>
                </div>
            @endif
            @if(!is_null($post->address))
                <p class="mt-2 text-gray-800 dark:text-neutral-200">食べた場所：{{ $post->address }}</p>
            @endif
        </div>
        
        <!-- 編集リンク -->
        @if(auth()->user()->id === $post->user_id)
            <div class="mt-6">
                <a href="/posts/{{ $post->id }}/edit" class="text-blue-600 hover:underline">edit</a>
            </div>
        @endif
        
        <!-- 戻るリンク -->
        <div class="footer mt-10">
            <a href="/" class="text-blue-600 hover:underline">戻る</a>
        </div>
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
    </script>
@endsection