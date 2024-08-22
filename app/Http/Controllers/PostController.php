<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use App\Models\Category;
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
        return view('posts.create')->with(['categories' => $category->get()]);
    }
    public function store(Post $post, PostRequest $request)
    {
        $input = $request->input('post');
        
        $post->fill($input);
        $post->user_id = auth()->id();
        $post->save();
        
        if ($request->hasFile('image')) {
            $images = $request->file('image');
            
            if (!is_array($images)) {
                $images = [$images];
            }
            
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