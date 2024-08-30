<!DOCTYPE HTML>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>プロフィール編集</title>
    <style>
        .profile img {
            cursor: pointer;
        }
        .profile input[type="file"] {
            display: none;
        }
    </style>
</head>
<body>
    
    <form method="POST" action="{{ route('users.update', Auth::user()) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
    
        <div class='profile'>
            <h3>ニックネーム</h3>
            <input type='text' name='name' value="{{ $user->name }}" />

            @if ($user->profile_image === null)
                <img class="rounded-circle" src="{{ asset('storage/default.png') }}" alt="プロフィール画像" width="100" height="100" id="profile-image-preview">
            @else
                <img class="rounded-circle" src="{{ $user->profile_image }}" alt="プロフィール画像" width="100" height="100" id="profile-image-preview">
            @endif
            
            <h3>プロフィール画像</h3>
            <input type="file" name="profile_image" id="profile-image-input" accept="image/png, image/jpeg" onchange="previewImage(event)">
        </div>
    
        <button type="submit" class="btn btn-primary">更新する</button>
    </form>
    <div class="footer">
        <a href="/">戻る</a>
    </div>

<script>
    // 画像クリックでファイル選択ダイアログを開く
    document.getElementById('profile-image-preview').addEventListener('click', function() {
        document.getElementById('profile-image-input').click();
    });

    // プレビュー画像を更新
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('profile-image-preview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

</body>
</html>
