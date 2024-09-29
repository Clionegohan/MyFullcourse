@extends('layouts.app')

@section('title', 'グルメダイアリー' . $diary->id . 'の詳細 - MyFullCourse')

@section('content')
    <div class="max-w-6xl px-4 py-12 mx-auto flex flex-col items-center text-center">
        
        <!-- User Section -->
        <div class="flex items-center justify-center mb-6">
            <a href="/users/{{ $diary->user->id }}" class="flex items-center text-[#810947]">
                @if ($diary->user->profile_image === null)
                    <img class="w-16 h-16 rounded-full object-cover" src="https://res.cloudinary.com/dem5z47a6/image/upload/v1726220971/default_icon_odkziu.png" alt="プロフィール画像">
                @else
                    <img class="w-16 h-16 rounded-full object-cover" src="{{ $diary->user->profile_image }}" alt="プロフィール画像">
                @endif
                <p class="ml-4 text-xl font-semibold">{{ $diary->user->name }}</p>
            </a>
        </div>        
        
        <!-- Content Section -->
        <div class="mt-10 bg-white p-8 rounded-lg shadow-lg max-w-2xl w-full">
            <div class="content__post text-left">
                <p class="mb-4 text-xl text-gray-900 text-center">日々のグルメ日記：</p>
                <p class="text-base text-gray-800 leading-relaxed">{{ $diary->content }}</p>
            </div>
            
            <!-- Images -->
            @if($diary->images->isNotEmpty())
                <div class="grid {{ $diary->images->count() > 1 ? 'grid-cols-2' : 'grid-cols-1'}} gap-4 mt-8 w-full">
                    @foreach($diary->images as $image)
                        <img src="{{ $image->image_url }}" alt="画像が読み込めません。" class="w-full h-auto rounded-lg shadow-md cursor-pointer" onclick="openModal('{{ $image->image_url }}')">
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Modal -->
        <div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75 flex items-center justify-center">
            <div class="relative">
                <button onclick="closeModal()" class="absolute top-0 right-0 mt-2 mr-2 text-white text-3xl">&times;</button>
                <img id="modalImage" src="" class="max-w-full max-h-screen object-contain">
            </div>
        </div>

        <!-- Comments Section -->
        @if($diary->comments->isNotEmpty())
            <div class="mt-8 bg-white p-6 rounded-lg shadow-lg max-w-2xl w-full text-left">
                <h2 class="text-lg font-bold mb-4">コメント</h2>
                @foreach($diary->comments as $comment)
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

        <!-- Delete Button -->
        @if(auth()->user()->id === $diary->user_id)
            <div class="mt-10 max-w-2xl w-full">
                <form action="/diaries/{{ $diary->id }}" id="form_{{ $diary->id }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="deleteDiary({{ $diary->id }})" 
                            class="bg-red-500 text-white rounded-lg px-4 py-2 cursor-pointer hover:bg-red-700">
                        削除
                    </button>
                </form>
            </div>
        @endif
        
        <!-- Back Link -->
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
            window.openModal = function(imageUrl) {
                document.getElementById('modalImage').src = imageUrl;
                document.getElementById('imageModal').classList.remove('hidden');
            }

            window.closeModal = function() {
                document.getElementById('imageModal').classList.add('hidden');
            }
        });
        
        // 削除確認ダイアログ
        function deleteDiary(id) {
            'use strict';
            
            if (confirm('削除すると復元できません。\n本当に削除しますか？')) {
                document.getElementById(`form_${id}`).submit();
            }
        }
    </script>
@endsection
