<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;


class CommentController extends Controller
{
    public function store(CommentRequest $request)
    {
        $user = auth()->user();
        
        $comment = $user->comments()->create([
            'comment' => $request->input('comment.body'),
            'post_id' =>$request->input('comment.post_id'),
            ]);
        return redirect()->back();
    }
    public function delete(Comment $comment)
    {
        $comment->delete();
        return redirect()->back();
    }
}
