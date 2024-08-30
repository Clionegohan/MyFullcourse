<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\Judgement;
use App\Models\Image;
use App\Models\Like;
use App\Http\Requests\PostRequest;
use Cloudinary;

class PostController extends Controller
{
    public function index(Post $post)
    {
        $posts = $post->getPaginateByLimit();
        return view('posts.index')->with(['posts' => $posts]);
    }
    public function show(Post $post)
    {
        $images = $post->images;
        return view('posts.show', compact('post', 'images'));
    }
    public function create(Category $category){
        $user = auth()->user();
        $judgement = Judgement::where('user_id', $user->id)->first();
        $categories = Category::all()->filter(function ($category) use ($judgement) {
            $judgementField = 'has_' . $category->name;
            return !$judgement || !$judgement->$judgementField;
        });
        
        return view('posts.create')->with(['categories' => $categories]);
    }
    public function store(Post $post, PostRequest $request)
    {
        $input = $request->input('post');
        $user = auth()->user();
        
        DB::transaction(function () use ($post, $input, $user, $request) {
        
            $category_id = $input['category_id'];
            $category = Category::find($category_id);

            $judgementField = 'has_' . $category->name;
            $judgement = Judgement::firstOrCreate(['user_id' => $user->id]);
            $judgement->update([$judgementField => 1]);

            $post->fill($input);
            $post->user_id = $user->id;
            $post->save();

            if ($request->hasFile('files')) {
                $images = $request->file('files');

                foreach ($images as $image) {
                    $image_url = Cloudinary::upload($image->getRealPath())->getSecurePath();
                    $post->images()->create(['image_url' => $image_url]);
                }
            }
        });
        
        return redirect('/posts/' .$post->id);
    }
    
    public function delete(Post $post)
    {
        $user = auth()->user();
        
        $category = $post->category;
        $judgementField = 'has_' . $category->name;
        $judgement = Judgement::where('user_id', $user->id)->first();
        if ($judgement) {
            $judgement->update([$judgementField => 0]);
        }
        
        $post->delete();
        return redirect('/');
    }
    
        public function edit(Post $post)
    {
        return view('posts.edit')->with(['post' => $post]);
    }
    public function update(PostRequest $request, Post $post)
    {
        $input = $request->input('post');
        $post->fill($input);
        $post->save();

        if ($request->has('delete_images')) {
            foreach ($request->input('delete_images') as $imageId) {
                $image = Image::find($imageId); // 画像IDに基づいて画像を取得
                if ($image) {
                    $image->delete();
                }
            }
        }
    
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $newImage) {
                $image_url = Cloudinary::upload($newImage->getRealPath())->getSecurePath();
                $post->images()->create(['image_url' => $image_url]);
            }
        }
        
        return redirect('/posts/' .$post->id);
    }
    
    public function like(Request $request, Post $post)
    {
        $user = auth()->user();
        
        
    //インデント汚い
        if ($post->likes()->where('user_id', $user->id)->exists()) {
            $post->likes()->where('user_id', $user->id)->delete();
        }else{
            $post->likes()->create(['user_id' => $user->id]);
        }
        
        $likesCount = $post->likes()->count();
        return response()->json(['success' => true, 'like_count' => $likesCount]);
    }
}