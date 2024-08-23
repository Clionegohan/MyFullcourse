<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\Judgement;
use App\Http\Requests\PostRequest;
use Cloudinary;

class PostController extends Controller
{
    public function index(Post $post)
    {
        $posts = $post->getPaginateByLimit();
        foreach ($posts as $post) {
            $images = $post->images;
        }
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
        
        $category_id = $input['category_id'];
        $category = Category::find($category_id);
        
        $judgementField = 'has_' . $category->name;
        $user = auth()->user();
        
        $judgement = Judgement::firstOrCreate(['user_id' => $user->id]);
            
        if ($judgement->$judgementField) {
            return redirect()->back()->withErrors(['category' =>'このカテゴリには既に投稿済みです。']);
        }
        
        $judgement->update([$judgementField => 1]);
        
        $post->fill($input);
        $post->user_id = $user->id;
        $post->save();
        
        if ($request->hasFile('files')) {
            $images = $request->file('files');    
            
            if (count($images) > 4) {
                return redirect()->back()->withErrors(['image' => '画像は最大4枚までアップロードできます。']);
            }
            
            DB::transaction(function () use ($images, $post) {
                foreach ($images as $image) {
                    $image_url = Cloudinary::upload($image->getRealPath())->getSecurePath();
                    $post->images()->create(['image_url' => $image_url]);
                }
            });
        }
        
        return redirect('/posts/' .$post->id);
    }
    
    public function delete(Post $post)
    {
        $user = auth()->user();
        
        $category = $post->category;
        $judgementField = 'has_' . $category->name;
        $judgement = Judgement::wehre('user_id', $user->id)->first();
        if ($judgement) {
            $judgement->update([$judgementField = 0]);
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

        return redirect('/posts/' .$post->id);
    }
}