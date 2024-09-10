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
use App\Http\Requests\SearchRequest;
use Cloudinary;

class SearchController extends Controller
{
    public function search(SearchRequest $request)
    {
        $keyword = $request->input('word');
        
        $posts = Post::where('title', 'like', "%{$keyword}%")
                     ->orWhere('body', 'like', "%{$keyword}%")
                     ->orWhere('address', 'like', "%{$keyword}%")
                     ->paginate(10);
                     
        return view('search.index', compact('posts', 'keyword'));
    }
}
