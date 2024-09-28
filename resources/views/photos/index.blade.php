@extends('layouts.app')

@section('title', '写真ギャラリー-MyFullCourse')

@section('content')
    <!-- Content -->
    <div class="container mx-auto px-4 py-10">
        <!-- Title -->
        <div class="text-center mb-10">
            <h1 class="text-2xl font-bold md:text-4xl md:leading-tight dark:text-white font-serif">写真ギャラリー</h1>
        </div>
        <!-- Gallery -->
        <div class="grid gap-4 grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
@foreach ($images->merge($diaryImages) as $image)
    @if ($image->post && $image->post->exists())
        <div class="relative group overflow-hidden">
            <a href="{{ route('posts.show', $image->post_id) }}">
                <img src="{{ $image->image_url }}" alt="画像" class="w-full h-full object-cover rounded-lg shadow-md transition-transform duration-300 group-hover:scale-105">
            </a>
        </div>
    @elseif ($image->diary && $image->diary->exists())
        <div class="relative group overflow-hidden">
            <a href="{{ route('diary.show', $image->diary_id) }}">
                <img src="{{ $image->image_url }}" alt="画像" class="w-full h-full object-cover rounded-lg shadow-md transition-transform duration-300 group-hover:scale-105">
            </a>
        </div>
    @endif
@endforeach

        </div>
        <!-- Modal -->
        <div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75 flex items-center justify-center">
            <div class="relative">
                <button onclick="closeModal()" class="absolute top-0 right-0 mt-2 mr-2 text-white text-3xl">&times;</button>
                <img id="modalImage" src="" class="max-w-full max-h-screen object-contain">
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function(){
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
