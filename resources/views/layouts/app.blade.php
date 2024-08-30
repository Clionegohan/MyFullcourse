<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- ページごとのタイトル -->
        <title>@yield('title', config('app.name', 'Laravel'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
　　　　<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">


        <!-- 子テンプレートで追加可能なCSS -->
        @yield('head')
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            <header class="text-gray-600 body-font">
                <div class="container mx-auto flex flex-wrap p-5 flex-col md:flex-row items-center">
                    <a class="flex items-center title-font font-medium text-gray-900 mb-4 md:mb-0">
                        <img src="{{ asset('storage/MyFullCourse_icon.png') }}" alt="MyFullCourse_icon Logo" class="w-16 h-16 rounded-full object-contain">
                        <span class="ml-3 text-xl">MyFullCourse</span>
                    </a>
                    <nav class="md:ml-auto flex flex-wrap items-center text-base justify-center">
                        <a href="/" class="mr-5 hover:text-gray-900">みんなのフルコース</a>
                        <a href="/posts/create" class="mr-5 hover:text-gray-900">料理の投稿</a>
                        <div class="relative group">
                            <button id="category-button" class="mr-5 hover:text-gray-900 focus:outline-none">カテゴリ</button>
                            <div id="category-menu" class="absolute hidden bg-white border mt-2 rounded shadow-lg z-10">
                                @foreach($categories as $category)
                                    <a href="{{ route('categories.index', ['category' => $category->id]) }}"
                                    class="block px-6 py-3 hover:bg-gray-200 text-gray-700 text-lg"
                                    style="min-width: 200px;">
                                        {{ $category->name_jp }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </nav>
                </div>
            </header>
            
            <!-- JavaScript -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const categoryButton = document.getElementById('category-button');
                    const categoryMenu = document.getElementById('category-menu');

                    // カテゴリボタンをクリックするとメニューを表示/非表示
                    categoryButton.addEventListener('click', function() {
                        categoryMenu.classList.toggle('hidden');
                    });

                    // ページの他の場所をクリックしたときにメニューを非表示にする
                    document.addEventListener('click', function(event) {
                        if (!categoryButton.contains(event.target) && !categoryMenu.contains(event.target)) {
                            categoryMenu.classList.add('hidden');
                        }
                    });
                });
            </script>

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>
    </body>
</html>
