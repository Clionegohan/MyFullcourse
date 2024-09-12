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

class LikeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $posts = Post::whereHas('likes', function($query) use ($user){
            $query->where('user_id', $user->id);
        })->paginate(10);
        
        return view('likes.index', compact('posts'));
    }
        public function like(Post $post)
    {
        $user = auth()->user();
        $like = $post->likes()->where('user_id', $user->id);

        if ($like->exists()) {
            $like->delete();
        }else{
            $post->likes()->create(['user_id' => $user->id]);
        }
        $likesCount = $post->likes()->count();
        
        return response()->json(['success' => true, 'like_count' => $likesCount]);
    }
}
