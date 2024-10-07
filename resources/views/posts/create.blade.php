@extends('layouts.app')

@section('title', 'フルコース投稿-MyFullCourse')

@section('content')
    <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto text-center">
        <h1 class="text-2xl font-bold md:text-4xl md:leading-tight font-serif">思い出の1品</h1>
        <form action="/posts" method="POST" enctype="multipart/form-data" class="mt-10 max-w-lg mx-auto text-left">
            @csrf
            <div class="category mb-6">
                <label for="category" class="block mb-2 text-sm font-medium text-gray-900">
                    カテゴリ <span class="text-red-500">*</span>
                </label>
                @if($categories->isEmpty())
                    <p>カテゴリがありません。</p>
                @else
                    <select name="post[category_id]" id="category" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="" disabled selected>カテゴリを選択してください。</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name_jp }}</option>
                        @endforeach
                    </select>
                    <p class="text-red-500">{{ $errors->first('post.category_id') }}</p>
                @endif
            </div>
            
            <div class="title mb-6">
                <label for="title" class="block mb-2 text-sm font-medium text-gray-900">
                    料理名 <span class="text-red-500">*</span>
                </label>
                <input type="text" name="post[title]" id="title" placeholder="どこで？誰が？などを書くといいかも！" value="{{ old('post.title') }}" 
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"/>
                <p class="text-red-500">{{ $errors->first('post.title') }}</p>
            </div>
            
            <div class="body mb-6">
                <label for="body" class="block mb-2 text-sm font-medium text-gray-900">
                    あなたの思い出の1品 <span class="text-red-500">*</span>
                </label>
                <textarea name="post[body]" id="body" placeholder="味の感想、料理の思い出をみんなと共有しよう！" 
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('post.body') }}</textarea>
                <p class="text-red-500">{{ $errors->first('post.body') }}</p>
            </div>
            
            <div class="image mb-6">
                <label for="files" class="block mb-2 text-sm font-medium text-gray-900">画像(ファイルサイズは1つ2MBまで！)</label>
                <input type="file" name="files[]" id="files" multiple
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"> 
                <p class="text-red-500">{{ $errors->first('files') }}</p>
            </div>
            
            <div class="map mb-6">
                <label for="address" class="block mb-2 text-sm font-medium text-gray-900">食べた場所</label>
                <input type="text" id="address" name="post[address]" placeholder="住所を入力すれば地図が表示されます。" onblur="geocoderAddress()" value="{{ old('post.address') }}" 
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <input type="hidden" id="latitude" name="post[latitude]" value="">
                <input type="hidden" id="longitude" name="post[longitude]" value="">
            </div>
            <div class="text-center">
                <input type="submit" value="投稿" class="bg-blue-500 text-white rounded-lg px-4 py-2 cursor-pointer hover:bg-blue-700"/>
            </div>
        </form>
        
        <div class="footer mt-6">
            <a href="javascript:void(0);" onclick="window.history.back();" class="text-blue-500 hover:underline">戻る</a>
        </div>
    </div>
    @php
        $hideSearchBox = true;
    @endphp
@endsection


@section('scripts')
    <script>
        /*
        function initMap() {
            const latitude = 35.6895;
            const longitude = 139.6917;
            
            const map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: latitude, lng: longitude },
                zoom: 15
            });
            
            const marker = new google.maps.Marker({
                position: { lat: latitude, lng: longitude },
            });
            
            console.log("Google Maps API Loaded");
        }
        */

        function geocoderAddress() {
            const address = document.getElementById('address').value;
            const geocoder = new google.maps.Geocoder();
            
            geocoder.geocode({ 'address': address }, function(results, status) {
                if (status === 'OK') {
                    const latitude = results[0].geometry.location.lat();
                    const longitude = results[0].geometry.location.lng();
                    
                    console.log("Latitude: " + latitude);
                    console.log("Longitude: " + longitude);
                    
                    document.getElementById('latitude').value = latitude;
                    document.getElementById('longitude').value = longitude;
                } else {
                    console.log('Geocode was not successful for the following reason: ' + status);
                }
            });
        }
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?language=ja&region=JP&key=AIzaSyD8p5NaW01uEm6AJVimJ75gyPwvV8obiTY&callback=initMap" async defer></script>
@endsection
