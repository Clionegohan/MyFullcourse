<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Diary;
use App\Models\DiaryImage;
use App\Models\DiaryLike;
use App\Http\Requests\DiaryRequest;
use Cloudinary;

class DiaryLikeController extends Controller
{
    public function like(Diary $diary) {
        $user = auth()->user();
        $like = $diary->likes()->where('user_id', $user->id);
        
        if ($like->exists()) {
            $like->delete();
        } else {
            $diary->likes()->create(['user_id'=>$user->id]);
        }
        $likesCount = $diary->likes()->count();
        
        return response()->json(['success' => true, 'like_count' => $likesCount]);
    }
}
