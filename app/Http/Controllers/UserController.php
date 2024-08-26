<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\Judgement;
use App\Models\Like;
use App\Http\Requests\PostRequest;
use Cloudinary;

class UserController extends Controller
{
    public function show(User $user) {
        $posts = Post::where('user_id', $user->id)
                     ->orderByCategory()
                     ->get();
        return view('users.show', compact('user', 'posts'));
    }
}
