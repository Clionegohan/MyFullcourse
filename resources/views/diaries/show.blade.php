@extends('layouts.app')

@section('title', 'グルメダイアリー' . $diary->id . 'の詳細-MyFullCourse')

@section('content')
    <div class="max-w-5xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto text-center">
        
        <!-- User -->
        <div class="flex items-center justify-center mb-4">
            <a href="/users/{{ $diary->user->id }}" class="flex items-center text-[#810947]">
                @if ($diary->user->profile_image === null)
                    <img class="w-12 h-12 rounded-full object-cover" src="https://res.cloudinary.com/dem5z47a6/image/upload/v1726220971/default_icon_odkziu.png" alt="プロフィール画像">
                @else
                    <img class="w-12 h-12 rounded-full object-cover" src="{{ $diary->user->profile_image }}" alt="プロフィール画像">
                @endif
                <p class="ml-3 font-semibold text-center">{{ $diary->user->name }}</p>
            </a>
        </div>        
        
        <!-- COntent -->
        <div class="mt-6 bg-white p-6 rounded-lg shadow-lg">
            <div class="content__post">
                <p class="mb-4 text-lg text-gray-900 dark:text-white">日々のグルメ日記：</p>
                <p class="text-base text-gray-800  leading-relaxed text-left">{{ $diary->content }}</p>
            </div>
            
            <!-- Image -->
            @if($diary->images->isNotEmpty())
                <div class="grid {{ $diary->images->count() > 1 ? 'grid-cols-2' : 'grid-cols-1'}} gap-4 mt-4 w-full">
                    @foreach($diary->images as $image)
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
        
        <!-- Edit -->
        @if(auth()->user()->id === $diary->user_id)
        <form action="/diaries/{{ $diary->id }}" id="form_{{ $diary->id }}" method="post" class="mt-6">
            @csrf
            @method('DELETE')
            <button type="button" onclick="deleteDiary({{ $diary->id }})" 
                    class="bg-red-500 text-white rounded-lg px-4 py-2 cursor-pointer hover:bg-red-700">
                削除
            </button>
        </form>
        @endif
        
        <!-- Back -->
        <div class="footer mt-10">
            <a href="/diaries" class="text-blue-600 hover:underline">戻る</a>
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
        
        function deleteDiary(id) {
            'use strict';
            
            if (confirm('削除すると復元できません。\n本当に削除しますか？')) {
                document.getElementById(`form_${id}`).submit();
            }
        }
    </script>
@endsection
