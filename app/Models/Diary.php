<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Diary extends Model
{
    use HasFactory;
    use softDeletes;
    protected $table = 'diaries';
    
    protected $fillable = [
        'user_id',
        'content',
    ];
    
    public function getPaginateByLimit(int $limit_count = 30)
    {
        return $this->orderBy('updated_at', 'DESC')->paginate($limit_count);
    }
    
    public function isLikedByUser($user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function images()
    {
        return $this->hasMany(DiaryImage::class);
    }
    public function likes()
    {
        return $this->hasMany(DiaryLike::class);
    }
    public function comments()
    {
        return $this->hasMany(DiaryComment::class);
    }
}
