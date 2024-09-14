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
        try {
            // 認証ユーザーを取得
            $user = auth()->user();
            Log::info('User authenticated: ' . $user->id); // ユーザーIDをログに記録

            // ユーザーが認証されていない場合の処理
            if (!$user) {
                Log::error('User not authenticated.');
                return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
            }

            // いいねを確認
            $like = $post->likes()->where('user_id', $user->id);
            Log::info('Like query executed for post_id: ' . $post->id); // クエリが実行されたことをログに記録

            // いいねが既に存在する場合は削除
            if ($like->exists()) {
                Log::info('Like exists, deleting like for user_id: ' . $user->id . ', post_id: ' . $post->id);
                $like->first()->delete();
            } else {
                // いいねが存在しない場合は作成
                Log::info('Creating like for user_id: ' . $user->id . ', post_id: ' . $post->id);
                $post->likes()->create(['user_id' => $user->id]);
            }

            // いいね数を取得
            $likesCount = $post->likes()->count();
            Log::info('Like count updated for post_id: ' . $post->id . ', new like count: ' . $likesCount);

            // 成功したレスポンスを返す
            return response()->json(['success' => true, 'like_count' => $likesCount]);

        } catch (\Exception $e) {
            // エラーメッセージをログに記録
            Log::error('Error in like function: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Something went wrong'], 500);
        }
    }
}