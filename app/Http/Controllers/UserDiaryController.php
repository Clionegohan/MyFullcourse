<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Diary;
use App\Models\Diary_image;
use App\Models\DiaryLike;
use App\Models\DiaryComment;
use App\Http\Requests\DiaryRequest;
use App\Http\Requests\DiaryCommentRequest;
use Cloudinary;

class UserDiaryController extends Controller
{
    public function index(User $user)
    {
        $diaries = $user->diaries()->orderBy('created_at', 'desc')->paginate(30);
        
        return view('users.diaries', compact('user', 'diaries'));
    }
}
