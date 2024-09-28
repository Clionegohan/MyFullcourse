<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Diary;
use App\Models\Diary_image;
use App\Models\DiaryLike;
use App\Models\DiaryComment;
use App\Http\Requests\DiaryCommentRequest;
use Cloudinary;

class DiaryCommentController extends Controller
{
    public function store (DiaryCommentRequest $request) {
        $user = auth()->user();
        
        $comment = $user->diarycomments()->create([
            'comment' => $request->input('comment.body'),
            'diary_id' => $request->input('comment.diary_id')
            ]);
    return redirect()->route('diary.index');
    }
    
    public function destroy(DiaryComment $comment) {
        $user = auth()->user();
        $comment->delete();
        return redirect()->route('diary.index');
    }
}
