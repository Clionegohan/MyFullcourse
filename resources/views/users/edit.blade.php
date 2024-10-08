@extends('layouts.app')

@section('title', 'プロフィール編集画面-MyFullCourse')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-8 text-center">プロフィール編集</h1>

        <form method="POST" action="{{ route('users.update', Auth::user()) }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="mb-8">
                <label for="name" class="block text-lg font-medium text-gray-700 mb-2 text-center">ニックネーム</label>
                <input type="text" name="name" id="name" value="{{ $user->name }}" 
                       class="w-full p-2 border border-gray-300 rounded-lg focus:ring focus:ring-indigo-200"/>
 
                <div class="mt-6 text-center">
                    @if ($user->profile_image === null)
                        <img class="w-24 h-24 rounded-full object-cover mx-auto" src="https://res.cloudinary.com/dem5z47a6/image/upload/v1726220971/default_icon_odkziu.png" 
                             alt="プロフィール画像" id="profile-image-preview"/>
                    @else
                        <img class="w-24 h-24 rounded-full object-cover mx-auto" src="{{ $user->profile_image }}" 
                             alt="プロフィール画像" id="profile-image-preview"/>
                    @endif
                </div>

                <h3 class="mt-6 text-lg font-medium text-gray-700 text-center">プロフィール画像</h3>
                <input type="file" name="profile_image" id="profile-image-input" accept="image/png, image/jpeg" 
                       class="hidden" onchange="previewImage(event)"/>
            </div>

            <div class="text-center">
                <button type="submit" class="bg-indigo-500 text-white px-6 py-2 rounded-lg hover:bg-indigo-600">
                    更新する
                </button>
            </div>
        </form>

        <!-- ログアウトボタン -->
        <div class="text-center mt-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600">
                    ログアウト
                </button>
            </form>
        </div>
 
        <div class="mt-8 text-center">
            <a href="javascript:void(0);" onclick="window.history.back();" class="text-blue-500 hover:underline">戻る</a>
        </div>
    </div>
    @php
        $hideSearchBox = true;
    @endphp
@endsection

@section('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 画像クリックでファイル選択ダイアログを開く
        var profileImagePreview = document.getElementById('profile-image-preview');
        var profileImageInput = document.getElementById('profile-image-input');

        if (profileImagePreview && profileImageInput) {
            profileImagePreview.addEventListener('click', function() {
                profileImageInput.click();
            });

            // プレビュー画像を更新
            profileImageInput.addEventListener('change', function(event) {
                var reader = new FileReader();
                reader.onload = function() {
                    profileImagePreview.src = reader.result;
                };
                reader.readAsDataURL(event.target.files[0]);
            });
        }
    });

</script>
@endsection
